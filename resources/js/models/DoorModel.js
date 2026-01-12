
// Modelo 3D realista para puertas/portones corredizos de aluminio
export class DoorModel {
    constructor(THREE) {
        this.THREE = THREE;
    }

    // Generar puerta completa
    generate(parameters) {
        console.log('🚪 DoorModel.generate() called with parameters:', parameters);
        
        const group = new this.THREE.Group();
        const { width, height, frameWidth } = parameters;
        const hojas = parameters.hojas || 2; // Por defecto 2 hojas
        
        console.log('🚪 Door dimensions:', { width, height, frameWidth, hojas });
        
        // Profundidad fija (no afecta precio)
        const depth = 0.15;

        // Configurar HDRI solo si no está cargado globalmente
        this.ensureHDRIEnvironment(parameters);

        // Materiales realistas con texturas dinámicas
        const materials = this.createRealisticMaterials(parameters);
        
        // Marco principal (Rieles y Jambas)
        const mainFrame = this.createMainFrame(width, height, depth, frameWidth, materials);
        group.add(mainFrame);

        // Hojas correderas con perfiles horizontales y verticales
        const slidingPanels = this.createSlidingPanelsWithRealComponents(hojas, width, height, depth, frameWidth, materials);
        group.add(slidingPanels);

        // Herrajes específicos (ruedas, cerraduras)
        const realHardware = this.createRealHardware(hojas, width, height, depth, frameWidth, materials);
        group.add(realHardware);

        // Configurar sombras
        this.setupShadows(group);

        // Agregar funcionalidad de deslizamiento (pasar el grupo completo como WindowModel)
        this.setupSlidingFunctionality(group, width, height, depth, frameWidth, hojas);
        
        // Hacer paneles clickeables si hay cámara y domElement
        if (parameters.camera && parameters.domElement) {
            this.makeInteractive(group, parameters.camera, parameters.domElement, width, frameWidth, hojas);
        }

        // Agregar función de actualización de colores al grupo
        group.userData.updateColors = (newParameters) => {
            this.updateColors(newParameters);
        };
        
        // Agregar función de prueba global para debugging
        window.testDoorSlide = () => {
            console.log('🧪 Testing door slide...');
            if (group.userData.slidePanel) {
                group.userData.slidePanel('1', 'toggle');
            } else {
                console.error('❌ slidePanel function not found on group.userData');
            }
        };
        
        // Almacenar referencia al modelo
        this.model = group;
        
        // Inicializar cache de elementos
        this.cacheModelElements();

        return group;
    }

    // Asegurar que el HDRI esté cargado globalmente (igual que WindowModel)
    ensureHDRIEnvironment(parameters) {
        const scene = parameters.scene;
        
        if (this.isHDRIAlreadyLoaded(parameters)) {
            return;
        }
        
        const hdriConfig = {
            primary: '/hdri/HdrOutdoorSnowMountainsEveningClear001_HDR_2K.exr',
            fallback: '/hdri/HdrOutdoorSnowMountainsEveningClear001_JPG_2K.jpg',
            preview: '/hdri/HdrOutdoorSnowMountainsEveningClear001_preview1.jpg'
        };
        
        this.markHDRIAsLoading();
        this.loadHDRIEnvironment(hdriConfig, parameters);
    }

    isHDRIAlreadyLoaded(parameters) {
        const scene = parameters.scene;
        if (window.globalHDRILoading === true) {
            return true;
        }
        const isMarkedAsLoaded = window.globalHDRILoaded === true;
        const hasEnvironment = scene && scene.environment;
        const hasBackground = scene && scene.background;
        return isMarkedAsLoaded && (hasEnvironment || hasBackground);
    }

    markHDRIAsLoading() {
        window.globalHDRILoading = true;
        window.globalHDRILoaded = false;
    }

    markHDRIAsLoaded() {
        window.globalHDRILoading = false;
        window.globalHDRILoaded = true;
    }

    loadHDRIEnvironment(hdriConfig, parameters) {
        const fileExtension = hdriConfig.primary.split('.').pop().toLowerCase();
        const isEXR = fileExtension === 'exr';
        const isHDR = fileExtension === 'hdr';
        const isJPG = fileExtension === 'jpg' || fileExtension === 'jpeg';
        
        const hdriLoaders = parameters.hdriLoaders;
        
        let loader;
        if (isJPG) {
            loader = new this.THREE.TextureLoader();
        } else {
            const LoaderClass = isEXR ? hdriLoaders?.EXRLoader : hdriLoaders?.RGBELoader;
            loader = new LoaderClass();
        }
        
        if (isHDR) {
            loader.setDataType(this.THREE.HalfFloatType);
        }
        
        loader.load(
            hdriConfig.primary,
            (texture) => {
                this.applyHDRIToScene(texture, parameters);
            },
            undefined,
            (error) => {
                console.warn('Error loading HDRI, trying fallback...', error);
                this.loadFallbackEnvironment(hdriConfig.fallback, parameters);
            }
        );
    }

    applyHDRIToScene(hdriTexture, parameters) {
        hdriTexture.mapping = this.THREE.EquirectangularReflectionMapping;
        hdriTexture.generateMipmaps = false;
        hdriTexture.minFilter = this.THREE.LinearFilter;
        hdriTexture.magFilter = this.THREE.LinearFilter;
        
        const renderer = parameters.renderer;
        if (!renderer) return;

        const pmremGenerator = new this.THREE.PMREMGenerator(renderer);
        pmremGenerator.compileEquirectangularShader();
        
        let envMap;
        try {
            envMap = pmremGenerator.fromEquirectangular(hdriTexture).texture;
        } catch (error) {
            console.error('Error generating PMREM:', error);
            envMap = hdriTexture;
        }
        
        const scene = parameters.scene;
        if (scene) {
            scene.environment = envMap;
        }

        this.envMap = envMap;
        this.markHDRIAsLoaded();

        hdriTexture.dispose();
        pmremGenerator.dispose();

        if (this.model && this.model.userData && typeof this.model.userData.updateColors === 'function') {
            this.model.userData.updateColors(parameters);
        }
    }

    loadFallbackEnvironment(fallbackPath, parameters) {
        if (!fallbackPath) return;
    
        const textureLoader = new this.THREE.TextureLoader();
        textureLoader.load(
            fallbackPath,
            (texture) => {
                this.applyHDRIToScene(texture, parameters);
            },
            undefined,
            (error) => {
                console.error('Failed to load fallback HDRI:', error);
                this.markHDRIAsLoaded();
            }
        );
    }

    // Cache de materiales y texturas
    materialCache = new Map();
    textureCache = new Map();

    // Crear materiales realistas con PBR avanzado y texturas dinámicas
    createRealisticMaterials(parameters) {
        const glassTexturePath = parameters.glassTexturePath || '/textures/glass/transparent/';
        let glassTexture = null;
        if (glassTexturePath) {
            const glassTextureFile = `${glassTexturePath}vidrio.jpg`;
            if (this.textureCache.has(glassTextureFile)) {
                glassTexture = this.textureCache.get(glassTextureFile);
            } else {
                try {
                    const textureLoader = new this.THREE.TextureLoader();
                    glassTexture = textureLoader.load(glassTextureFile);
                    glassTexture.wrapS = this.THREE.RepeatWrapping;
                    glassTexture.wrapT = this.THREE.RepeatWrapping;
                    this.textureCache.set(glassTextureFile, glassTexture);
                } catch (error) {
                    console.warn('Error loading glass texture:', error);
                    glassTexture = null;
                }
            }
        }

        let texturePath = parameters.texturePath || '/textures/aluminum/natural/';
        let useWhiteTexture = false;
        if (parameters.color && parameters.color.toLowerCase() === 'white') {
            texturePath = '/textures/aluminum/white/';
            useWhiteTexture = true;
        }

        let baseColor, roughness, normal, metalness, displacement;
        let aluminumMaterial;
        if (texturePath.includes('/aluminum/')) {
            const basePath = texturePath.endsWith('/') ? texturePath : texturePath + '/';
            let textureSet = this.textureCache.get(basePath);
            if (!textureSet) {
                textureSet = this.loadTextureSet(basePath);
                this.textureCache.set(basePath, textureSet);
            }
            ({ baseColor, roughness, normal, metalness, displacement } = textureSet);

            const materialConfig = {
                metalness: 0.8,
                roughness: 0.4,
                envMapIntensity: 0.3,
                clearcoat: 0.1,
                clearcoatRoughness: 0.2,
                envMap: this.envMap || null,
                opacity: 1.0,
                transparent: false,
            };
            
            if (baseColor) {
                materialConfig.map = baseColor;
            }
            if (metalness) {
                materialConfig.normalMap = metalness;
                materialConfig.normalScale = new this.THREE.Vector2(0.3, 0.3);
            }
            if (roughness) {
                materialConfig.roughnessMap = roughness;
                materialConfig.aoMap = roughness;
                materialConfig.aoMapIntensity = 0.2;
            }
            if (displacement) {
                materialConfig.displacementMap = displacement;
                materialConfig.displacementScale = 0.005;
                materialConfig.displacementBias = -0.002;
            }
            if (useWhiteTexture) {
                materialConfig.color = new this.THREE.Color('#F8F8FF');
                materialConfig.metalness = 0.4;
            }

            aluminumMaterial = new this.THREE.MeshPhysicalMaterial(materialConfig);
        } else {
            aluminumMaterial = new this.THREE.MeshPhysicalMaterial({
                color: 0xB0B0B0,
                metalness: 0.9,
                roughness: 0.2,
                clearcoat: 0.5,
                clearcoatRoughness: 0.1,
                envMapIntensity: 2.0,
                envMap: this.envMap || null
            });
        }

        return {
            aluminum: aluminumMaterial,
            glass: new this.THREE.MeshPhysicalMaterial({
                map: glassTexture || null,
                transparent: true,
                transmission: 1.0,
                opacity: 1.0,
                metalness: 0.7,
                roughness: 0.05,
                transmission: 0.001,
                ior: 1.52,
                thickness: 0.01,
                specularIntensity: 4.0,
                envMapIntensity: 5.0,
                envMap: this.envMap || null,
                clearcoat: 1.0,
                clearcoatRoughness: 0.05,
                side: this.THREE.DoubleSide
            }),
            stainlessSteel: new this.THREE.MeshPhysicalMaterial({
                color: 0xE8E8E8,
                metalness: 0.95,
                roughness: 0.1,
                clearcoat: 0.4,
                clearcoatRoughness: 0.02,
                envMapIntensity: 1.5,
                normalScale: new this.THREE.Vector2(0.03, 0.03)
            })
        };
    }

    loadTextureSet(basePath) {
        const textureLoader = new this.THREE.TextureLoader();
        
        let baseColor, roughness, normal, metalness, displacement;
        
        try {
            baseColor = textureLoader.load(
                `${basePath}Metal050A_1K-JPG_Color.jpg`
            );
            
            roughness = textureLoader.load(
                `${basePath}Metal050A_1K-JPG_Roughness.jpg`
            );
            
            normal = textureLoader.load(
                `${basePath}Metal050A_1K-JPG_NormalGL.jpg`
            );
            
            metalness = textureLoader.load(
                `${basePath}Metal050A_1K-JPG_Metalness.jpg`
            );
            
            displacement = textureLoader.load(
                `${basePath}Metal050A_1K-JPG_Displacement.jpg`
            );
            
            // Configurar propiedades de las texturas con configuración optimizada
            [baseColor, roughness, normal, metalness, displacement].forEach(texture => {
                if (texture) {
                    texture.wrapS = this.THREE.RepeatWrapping;
                    texture.wrapT = this.THREE.RepeatWrapping;
                    texture.repeat.set(1, 1);
                    texture.generateMipmaps = true;
                    texture.minFilter = this.THREE.LinearMipmapLinearFilter;
                    texture.magFilter = this.THREE.LinearFilter;
                    texture.anisotropy = 4;
                }
            });
            
        } catch (error) {
            console.warn('Error loading textures:', error);
        }
        
        return { baseColor, roughness, normal, metalness, displacement };
    }

    // Actualizar colores dinámicamente
    updateColors(parameters) {
        if (!this.model) return;
        
        const texturePath = parameters.texturePath || '/textures/aluminum/natural/';
        const glassTexturePath = parameters.glassTexturePath || '/textures/glass/transparent/';
        const glassColor = parameters.glassColor || '';
        const colorKey = `${texturePath}|${glassTexturePath}|${glassColor}`;
        
        if (!this.materialCache.has(colorKey)) {
            const materials = this.createRealisticMaterials(parameters);
            this.materialCache.set(colorKey, materials);
        }
        
        const materials = this.materialCache.get(colorKey);
        
        if (!this.modelElements) {
            this.cacheModelElements();
        }
        
        this.updateElementMaterials(materials);
    }

    cacheModelElements() {
        if (!this.model) return;
        
        this.modelElements = {
            aluminum: [],
            glass: [],
            stainlessSteel: []
        };
        
        this.model.traverse((child) => {
            if (child.isMesh) {
                if (child.name === 'vidrio' || child.material?.transmission > 0.5) {
                    this.modelElements.glass.push(child);
                } else if (child.material?.metalness > 0.7 && child.material?.roughness < 0.3) {
                    this.modelElements.stainlessSteel.push(child);
                } else {
                    this.modelElements.aluminum.push(child);
                }
            }
        });
    }

    updateElementMaterials(materials) {
        this.modelElements.aluminum.forEach(element => {
            element.material = materials.aluminum;
            element.material.needsUpdate = true;
        });
        
        this.modelElements.glass.forEach(element => {
            element.material = materials.glass;
            element.material.needsUpdate = true;
        });
        
        this.modelElements.stainlessSteel.forEach(element => {
            element.material = materials.stainlessSteel;
            element.material.needsUpdate = true;
        });
    }

    // Marco principal (Rieles y Jambas) - más robusto para puerta
    createMainFrame(width, height, depth, frameWidth, materials) {
        const frameGroup = new this.THREE.Group();
        frameGroup.name = 'mainFrame';
        
        const frameProfiles = [
            { 
                create: () => this.createRailProfile(width, frameWidth * 1.2, depth),
                name: 'railSuperior', 
                position: { y: (height - frameWidth) / 2 },
                material: materials.aluminum
            },
            { 
                create: () => this.createRailProfile(width, frameWidth * 1.2, depth),
                name: 'railInferior', 
                position: { y: -(height - frameWidth) / 2 },
                material: materials.aluminum
            },
            { 
                create: () => this.createJambProfile(frameWidth * 1.2, height - (frameWidth * 2.4), depth),
                name: 'jambaIzquierda', 
                position: { x: -(width - frameWidth * 1.2) / 2 },
                material: materials.aluminum
            },
            { 
                create: () => this.createJambProfile(frameWidth * 1.2, height - (frameWidth * 2.4), depth),
                name: 'jambaDerecha', 
                position: { x: (width - frameWidth * 1.2) / 2 },
                material: materials.aluminum
            }
        ];
        
        frameProfiles.forEach(config => {
            const profile = config.create();
            profile.name = config.name;
            this.applyMaterialToProfile(profile, config.material);
            Object.assign(profile.position, config.position);
            frameGroup.add(profile);
        });
        
        return frameGroup;
    }

    createRailProfile(width, height, depth) {
        const railGroup = new this.THREE.Group();
        
        const mainBody = new this.THREE.BoxGeometry(width, height, depth);
        const mainMesh = new this.THREE.Mesh(mainBody);
        railGroup.add(mainMesh);
        
        const channelWidth = width * 0.85;
        const channelGeometry = new this.THREE.BoxGeometry(channelWidth, height * 0.25, depth * 0.5);
        
        const mainChannel = new this.THREE.Mesh(channelGeometry);
        mainChannel.position.z = 0;
        mainChannel.position.y = -height * 0.15;
        railGroup.add(mainChannel);
        
        return railGroup;
    }

    createJambProfile(width, height, depth) {
        const jambGroup = new this.THREE.Group();
        
        const mainGeometry = new this.THREE.BoxGeometry(width, height, depth);
        const mainMesh = new this.THREE.Mesh(mainGeometry);
        jambGroup.add(mainMesh);
        
        const grooveGeometry = new this.THREE.BoxGeometry(width * 0.3, height * 0.98, depth * 0.4);
        const groove = new this.THREE.Mesh(grooveGeometry);
        groove.position.x = width * 0.15;
        groove.position.z = depth * 0.15;
        jambGroup.add(groove);
        
        return jambGroup;
    }

    applyMaterialToProfile(profile, material) {
        profile.traverse((child) => {
            if (child.isMesh) {
                child.material = material;
                child.material.needsUpdate = true;
            }
        });
    }

    // Hojas deslizantes con componentes reales (Horizontales, Verticales, Vidrio)
    createSlidingPanelsWithRealComponents(hojas, width, height, depth, frameWidth, materials) {
        const slidingGroup = new this.THREE.Group();
        slidingGroup.name = 'slidingPanels';
        
        const panelWidth = (width - frameWidth * 2) / hojas;
        const panelHeight = height - frameWidth * 2.4;
        // Perfiles de hojas más anchos que el marco exterior
        const horizontalHeight = frameWidth * 3;  // Altura del perfil horizontal más grueso
        const verticalWidth = frameWidth * 2;      // Ancho del perfil vertical más grueso
        
        for (let i = 0; i < hojas; i++) {
            const panel = new this.THREE.Group();
            panel.name = `slidingPanel${i + 1}`;
            
            const horizontalSuperior = this.createHorizontalProfile(panelWidth, horizontalHeight, depth * 0.8);
            horizontalSuperior.name = 'horizontalSuperior';
            this.applyMaterialToProfile(horizontalSuperior, materials.aluminum);
            horizontalSuperior.position.y = panelHeight / 2 - horizontalHeight / 2;
            panel.add(horizontalSuperior);
            
            const horizontalInferior = this.createHorizontalProfile(panelWidth, horizontalHeight, depth * 0.8);
            horizontalInferior.name = 'horizontalInferior';
            this.applyMaterialToProfile(horizontalInferior, materials.aluminum);
            horizontalInferior.position.y = -panelHeight / 2 + horizontalHeight / 2;
            panel.add(horizontalInferior);
            
            const verticalIzquierdo = this.createVerticalProfile(verticalWidth, panelHeight - horizontalHeight * 2, depth * 0.8);
            verticalIzquierdo.name = 'verticalIzquierdo';
            this.applyMaterialToProfile(verticalIzquierdo, materials.aluminum);
            verticalIzquierdo.position.x = -panelWidth / 2 + verticalWidth / 2;
            panel.add(verticalIzquierdo);
            
            const verticalDerecho = this.createVerticalProfile(verticalWidth, panelHeight - horizontalHeight * 2, depth * 0.8);
            verticalDerecho.name = 'verticalDerecho';
            this.applyMaterialToProfile(verticalDerecho, materials.aluminum);
            verticalDerecho.position.x = panelWidth / 2 - verticalWidth / 2;
            panel.add(verticalDerecho);
            
            const glassWidth = panelWidth - verticalWidth * 2 - 0.02;
            const glassHeight = panelHeight - horizontalHeight * 2 - 0.02;
            const glassGeometry = new this.THREE.PlaneGeometry(glassWidth, glassHeight);
            const glass = new this.THREE.Mesh(glassGeometry, materials.glass);
            glass.name = 'vidrio';
            glass.position.z = 0;
            panel.add(glass);
            
            // Agregar cerradura al panel
            const lockWidth = 0.1;
            const lockHeight = 0.35;
            const lockDepth = 0.04;
            const lockGeometry = new this.THREE.BoxGeometry(lockWidth, lockHeight, lockDepth);
            const lock = new this.THREE.Mesh(lockGeometry, materials.stainlessSteel);
            lock.name = `cerradura${i + 1}`;
            
            // Posicionar cerradura en el borde del panel (relativa al panel)
            if (i === 0) {
                // Hoja izquierda: cerradura en borde izquierdo
                lock.position.x = -panelWidth / 2 + lockWidth / 2;
            } else {
                // Hoja derecha: cerradura en borde derecho
                lock.position.x = panelWidth / 2 - lockWidth / 2;
            }
            lock.position.y = 0;
            lock.position.z = depth * 0.4;
            panel.add(lock);
            
            // Agregar agujero al panel
            const holeRadius = 0.035;
            const holeDepth = lockWidth * 1.1;
            const holeGeometry = new this.THREE.CylinderGeometry(holeRadius, holeRadius, holeDepth, 32);
            const hole = new this.THREE.Mesh(holeGeometry, materials.stainlessSteel);
            hole.rotation.z = Math.PI;
            hole.position.x = lock.position.x;
            hole.position.y = lock.position.y;
            hole.position.z = lock.position.z;
            panel.add(hole);
            
            const offsetX = (panelWidth * (i - (hojas - 1) / 2));
            panel.position.x = offsetX;
            panel.userData.initialX = offsetX;
            panel.userData.panelIndex = i;
            slidingGroup.add(panel);
        }
        
        return slidingGroup;
    }

    createHorizontalProfile(width, height, depth) {
        const horizontalGroup = new this.THREE.Group();
        
        const mainGeometry = new this.THREE.BoxGeometry(width, height, depth);
        const mainMesh = new this.THREE.Mesh(mainGeometry);
        horizontalGroup.add(mainMesh);
        
        const innerGeometry = new this.THREE.BoxGeometry(width * 0.9, height * 0.4, depth * 0.4);
        const innerMesh = new this.THREE.Mesh(innerGeometry);
        innerMesh.position.z = -depth * 0.15;
        horizontalGroup.add(innerMesh);
        
        return horizontalGroup;
    }

    createVerticalProfile(width, height, depth) {
        const verticalGroup = new this.THREE.Group();
        
        const mainGeometry = new this.THREE.BoxGeometry(width, height, depth);
        const mainMesh = new this.THREE.Mesh(mainGeometry);
        verticalGroup.add(mainMesh);
        
        const chamferGeometry = new this.THREE.BoxGeometry(width * 0.3, height * 0.98, depth * 0.3);
        const chamfer = new this.THREE.Mesh(chamferGeometry);
        chamfer.position.z = depth * 0.2;
        verticalGroup.add(chamfer);
        
        return verticalGroup;
    }

    // Hardware realista (cerraduras ahora están integradas en los paneles)
    createRealHardware(hojas, width, height, depth, frameWidth, materials) {
        const hardwareGroup = new this.THREE.Group();
        hardwareGroup.name = 'hardware';
        
        // Las cerraduras ahora están integradas en cada panel deslizante
        // Este método se mantiene por compatibilidad pero ya no contiene hardware
        
        return hardwareGroup;
    }

    // Configurar funcionalidad de deslizamiento
    setupSlidingFunctionality(group, width, height, depth, frameWidth, hojas) {
        const panelWidth = (width - frameWidth * 2) / hojas;
        const windowDepth = depth;
        
        console.log('🔧 Setting up sliding functionality:', { hojas, panelWidth, depth });
        
        // Obtener el grupo de paneles deslizantes
        const slidingGroup = group.getObjectByName('slidingPanels');
        
        if (!slidingGroup) {
            console.error('❌ No se encontró el grupo slidingPanels');
            return;
        }
        
        // Inicializar userData para cada panel dentro del slidingGroup
        slidingGroup.children.forEach((panel, index) => {
            if (panel.name && panel.name.includes('slidingPanel')) {
                console.log(`📍 Configuring panel ${panel.name}:`, {
                    initialX: panel.userData.initialX,
                    position: panel.position.x
                });
                
                panel.userData.isOpen = false;
                panel.userData.slideDistance = width * 0.25;
                panel.userData.originalX = panel.userData.initialX || panel.position.x;
                panel.userData.originalZ = panel.position.z;
                panel.userData.currentDisplacement = 0;
                
                console.log(`✅ Panel ${panel.name} configured:`, {
                    originalX: panel.userData.originalX,
                    originalZ: panel.userData.originalZ
                });
            }
        });
        
        // Función para deslizar panel específico - aplicada al GRUPO principal
        group.userData.slidePanel = (panelNumber, action = 'toggle') => {
            const slidingGroup = group.getObjectByName('slidingPanels');
            if (!slidingGroup) return;
            
            const panel = slidingGroup.children.find(p => p.name === `slidingPanel${panelNumber}`);
            const otherPanel = slidingGroup.children.find(p => p.name === `slidingPanel${panelNumber === '1' ? '2' : '1'}`);
            
            if (!panel) {
                console.warn(`⚠️ Panel ${panelNumber} no encontrado`);
                return;
            }
            
            console.log(`🚪 Sliding panel ${panelNumber}, action: ${action}, isOpen: ${panel.userData.isOpen}`);
            
            const duration = 800;
            let targetX, targetZ;
            
            if (action === 'toggle') {
                if (panel.userData.isOpen) {
                    // CERRAR: volver a posición original
                    targetX = panel.userData.originalX;
                    targetZ = panel.userData.originalZ;
                } else {
                    // ABRIR: mover sobre la otra hoja (como WindowModel)
                    if (panelNumber === '1') {
                        // Hoja 1 se desliza hacia la derecha encima de hoja 2
                        targetX = otherPanel ? otherPanel.userData.originalX : panel.userData.originalX + panel.userData.slideDistance;
                        targetZ = windowDepth / 6; // Plano frontal para estar encima
                    } else {
                        // Hoja 2 se desliza hacia la izquierda encima de hoja 1
                        targetX = otherPanel ? otherPanel.userData.originalX : panel.userData.originalX - panel.userData.slideDistance;
                        targetZ = windowDepth / 6; // Plano frontal para estar encima
                    }
                }
            }
            
            // Animar tanto X como Z
            this.animateSlidingWithDepth(panel, targetX, targetZ, duration);
            panel.userData.isOpen = !panel.userData.isOpen;
        };
        
        // Función para abrir/cerrar todas las puertas
        group.userData.slideDoor = (action = 'toggle') => {
            const slidingGroup = group.getObjectByName('slidingPanels');
            if (!slidingGroup) return;
            
            slidingGroup.children.forEach((panel, index) => {
                if (panel.name && panel.name.includes('slidingPanel')) {
                    const panelNumber = panel.name.replace('slidingPanel', '');
                    setTimeout(() => {
                        group.userData.slidePanel(panelNumber, action);
                    }, index * 200);
                }
            });
        };
        
        // Función para click general en la puerta
        group.userData.onClick = () => {
            group.userData.slideDoor('toggle');
        };
        
        // Función para click en panel específico
        group.userData.onPanelClick = (panelNumber) => {
            group.userData.slidePanel(panelNumber, 'toggle');
        };
        
        // Función para resetear posiciones
        group.userData.resetPanels = () => {
            const slidingGroup = group.getObjectByName('slidingPanels');
            if (!slidingGroup) return;
            
            slidingGroup.children.forEach((panel) => {
                if (panel.name && panel.name.includes('slidingPanel') && panel.userData) {
                    panel.position.x = panel.userData.originalX;
                    panel.userData.isOpen = false;
                    panel.userData.slideRange.current = 0;
                    panel.userData.currentDisplacement = 0;
                }
            });
        };
        
        // Estado de la puerta
        group.userData.getDoorState = () => {
            const slidingGroup = group.getObjectByName('slidingPanels');
            if (!slidingGroup) return {};
            
            const panelStates = {};
            let openCount = 0;
            
            slidingGroup.children.forEach((panel) => {
                if (panel.name && panel.name.includes('slidingPanel')) {
                    const panelNumber = panel.name.replace('slidingPanel', '');
                    panelStates[`panel${panelNumber}Open`] = panel.userData.isOpen;
                    if (panel.userData.isOpen) openCount++;
                }
            });
            
            return {
                ...panelStates,
                fullyOpen: openCount === hojas,
                partiallyClosed: openCount > 0 && openCount < hojas,
                fullyClosed: openCount === 0
            };
        };
    }
    
    // Animación de deslizamiento con profundidad (X y Z)
    animateSlidingWithDepth(panel, targetX, targetZ, duration) {
        if (!panel) return;
        
        const startX = panel.position.x;
        const startZ = panel.position.z;
        const distanceX = targetX - startX;
        const distanceZ = targetZ - startZ;
        const startTime = Date.now();
        
        // Cancelar animación anterior si existe
        if (panel.userData.animationId) {
            cancelAnimationFrame(panel.userData.animationId);
        }
        
        const animate = () => {
            const elapsed = Date.now() - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Easing suave (ease-out-cubic)
            const easeProgress = 1 - Math.pow(1 - progress, 3);
            
            panel.position.x = startX + distanceX * easeProgress;
            panel.position.z = startZ + distanceZ * easeProgress;
            
            // Almacenar desplazamiento actual
            panel.userData.currentDisplacement = distanceX * easeProgress;
            
            if (progress < 1) {
                panel.userData.animationId = requestAnimationFrame(animate);
            } else {
                // Animación completada
                delete panel.userData.animationId;
                // Asegurar posición final exacta
                panel.position.x = targetX;
                panel.position.z = targetZ;
            }
        };
        
        animate();
    }

    // Hacer interactivo
    makeInteractive(model, camera, domElement, width, frameWidth, hojas) {
        const raycaster = new this.THREE.Raycaster();
        const mouse = new this.THREE.Vector2();
        
        const onMouseDown = (event) => {
            event.preventDefault();
            mouse.x = (event.clientX / domElement.clientWidth) * 2 - 1;
            mouse.y = -(event.clientY / domElement.clientHeight) * 2 + 1;
            
            raycaster.setFromCamera(mouse, camera);
            const intersects = raycaster.intersectObjects(model.children, true);
            
            if (intersects.length > 0) {
                let panel = intersects[0].object;
                
                // Buscar el panel padre
                while (panel.parent && !panel.name?.includes('slidingPanel')) {
                    panel = panel.parent;
                }
                
                // Si encontramos un panel, activar la animación
                if (panel.name && panel.name.includes('slidingPanel')) {
                    const panelNumber = panel.name.replace('slidingPanel', '');
                    console.log(`🖱️ Click on panel ${panelNumber}`);
                    if (model.userData.slidePanel) {
                        model.userData.slidePanel(panelNumber, 'toggle');
                    }
                }
            }
        };
        
        const onMouseMove = (event) => {
            mouse.x = (event.clientX / domElement.clientWidth) * 2 - 1;
            mouse.y = -(event.clientY / domElement.clientHeight) * 2 + 1;
            raycaster.setFromCamera(mouse, camera);
            const intersects = raycaster.intersectObjects(model.children, true);
            
            if (intersects.length > 0) {
                let panel = intersects[0].object;
                while (panel.parent && !panel.name?.includes('slidingPanel')) {
                    panel = panel.parent;
                }
                
                if (panel.name && panel.name.includes('slidingPanel')) {
                    domElement.style.cursor = 'pointer';
                } else {
                    domElement.style.cursor = 'default';
                }
            } else {
                domElement.style.cursor = 'default';
            }
        };
        
        // Agregar controles de teclado
        const onKeyPress = (event) => {
            if (!model.userData.slidePanel) return;
            
            switch(event.key) {
                case '1':
                    model.userData.slidePanel('1', 'toggle');
                    break;
                case '2':
                    model.userData.slidePanel('2', 'toggle');
                    break;
                case ' ': // Barra espaciadora
                    event.preventDefault();
                    if (model.userData.slideDoor) {
                        model.userData.slideDoor('toggle');
                    }
                    break;
                case 'r':
                case 'R':
                    if (model.userData.resetPanels) {
                        model.userData.resetPanels();
                    }
                    break;
            }
        };
        
        domElement.addEventListener('mousedown', onMouseDown);
        domElement.addEventListener('mousemove', onMouseMove);
        document.addEventListener('keypress', onKeyPress);
        
        return { onMouseDown, onMouseMove, onKeyPress };
    }

    // Configurar sombras
    setupShadows(model) {
        model.traverse((child) => {
            if (child.isMesh) {
                child.castShadow = true;
                child.receiveShadow = true;
            }
        });
    }
}

// Exposición global para compatibilidad
if (typeof window !== 'undefined') {
    window.DoorModel = DoorModel;
}
