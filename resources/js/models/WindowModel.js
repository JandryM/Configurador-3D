// Modelo 3D realista para ventanas de aluminio
export class WindowModel {
    constructor(THREE) {
        this.THREE = THREE;
    }

    // Generar ventana completa
    generate(parameters) {
        const group = new this.THREE.Group();
        const { width, height, frameWidth } = parameters;
        
        // Profundidad fija de 1 metro (no afecta precio)
        const depth = 0.12;

        // Configurar HDRI solo si no está cargado globalmente (OPTIMIZADO)
        this.ensureHDRIEnvironment(parameters);

        // Materiales realistas
        const materials = this.createRealisticMaterials(parameters);
        
        // Componentes específicos según materiales reales
        
        // 1. Marco principal (Rieles y Jambas)
        const mainFrame = this.createMainFrame(width, height, depth, frameWidth, materials);
        group.add(mainFrame);
        
        // 2. Hojas correderas (2 hojas con perfiles horizontales y verticales) - AGREGADAS DIRECTAMENTE
        this.createSlidingPanelsWithRealComponents(width, height, depth, frameWidth, materials, group);
        
        // 3. Herrajes específicos (ruedas, seguro, tornillos)
        const realHardware = this.createRealHardware(width, height, depth, materials);
        group.add(realHardware);
        
        // 4. Sellado (felpa y caucho) - Solo para cálculo de costos (no visible)

        // Configurar sombras
        this.setupShadows(group);

        // Agregar funcionalidad de deslizamiento mejorada
        this.setupSlidingFunctionality(group, width, height, depth, frameWidth);
        
        // Hacer paneles clickeables individualmente
        this.makeInteractive(group);

        // Agregar función de actualización de colores al grupo
        group.userData.updateColors = (newParameters) => {
            this.updateColors(newParameters);
        };
        
        // Almacenar referencia al modelo para actualizaciones
        this.model = group;
        
        // Inicializar cache de elementos del modelo después de crearlo
        this.cacheModelElements();

        // Agregar función de prueba global para debugging
        window.testWindowSlide = () => {
            if (group.userData.slidePanel) {
                group.userData.slidePanel('1', 'toggle');
            }
        };

        return group;
    }

    // Asegurar que el HDRI esté cargado globalmente (SOLO UNA VEZ)
    ensureHDRIEnvironment(parameters) {
        const scene = parameters.scene;
        
        // Debug del estado actual
        console.log('🔍 Estado HDRI:', {
            globalLoaded: window.globalHDRILoaded,
            globalLoading: window.globalHDRILoading,
            hasEnvironment: scene && !!scene.environment,
            hasBackground: scene && !!scene.background
        });
        
        // Verificar si ya existe un environment global cargado
        if (this.isHDRIAlreadyLoaded(parameters)) {
            console.log('✅ HDRI ya está cargado globalmente, reutilizando...');
            return;
        }
        
        console.log('🚀 Cargando HDRI por primera vez...');
        
        // Configuración de rutas HDRI con fallbacks mejorados
        const hdriConfig = {
            primary: '/hdri/HdrOutdoorSnowMountainsEveningClear001_HDR_2K.exr',
            fallback: '/hdri/HdrOutdoorSnowMountainsEveningClear001_JPG_2K.jpg',
            preview: '/hdri/HdrOutdoorSnowMountainsEveningClear001_preview1.jpg'
        };
        
        // Marcar que se está cargando para evitar cargas duplicadas
        this.markHDRIAsLoading();
        
        // Intentar cargar HDRI con mejor manejo de errores
        this.loadHDRIEnvironment(hdriConfig, parameters);
    }
    
    // Verificar si el HDRI ya está cargado en la escena
    isHDRIAlreadyLoaded(parameters) {
        const scene = parameters.scene;
        
        // Si se está cargando actualmente, no cargar de nuevo
        if (window.globalHDRILoading === true) {
            return true;
        }
        
        // Verificar si está marcado como cargado Y realmente existe en la escena
        const isMarkedAsLoaded = window.globalHDRILoaded === true;
        const hasEnvironment = scene && scene.environment;
        const hasBackground = scene && scene.background;
        
        // Solo devolver true si está marcado como cargado Y realmente existe en la escena
        return isMarkedAsLoaded && (hasEnvironment || hasBackground);
    }
    
    // Marcar que el HDRI se está cargando
    markHDRIAsLoading() {
        window.globalHDRILoading = true;
        window.globalHDRILoaded = false;
    }
    
    // Marcar que el HDRI se ha cargado exitosamente
    markHDRIAsLoaded() {
        window.globalHDRILoading = false;
        window.globalHDRILoaded = true;
        console.log('✅ HDRI cargado exitosamente y marcado como global');
    }

    // Cargar HDRI real con fallback automático (soporta .hdr, .exr y .jpg)
    loadHDRIEnvironment(hdriConfig, parameters) {
        // Detectar tipo de archivo
        const fileExtension = hdriConfig.primary.split('.').pop().toLowerCase();
        const isEXR = fileExtension === 'exr';
        const isHDR = fileExtension === 'hdr';
        const isJPG = fileExtension === 'jpg' || fileExtension === 'jpeg';
        
        if (!isEXR && !isHDR && !isJPG) {
            this.loadFallbackEnvironment(hdriConfig.fallback, parameters);
            return;
        }

        // Verificar loaders disponibles desde parámetros
        const hdriLoaders = parameters.hdriLoaders;
        
        if (isEXR && !hdriLoaders?.EXRLoader) {
            this.loadFallbackEnvironment(hdriConfig.fallback, parameters);
            return;
        }
        
        if (isHDR && !hdriLoaders?.RGBELoader) {
            this.loadFallbackEnvironment(hdriConfig.fallback, parameters);
            return;
        }

        // Para JPG, usar TextureLoader normal - no necesita loaders especiales
        let loader;
        if (isJPG) {
            loader = new this.THREE.TextureLoader();
        } else {
            // Crear loader apropiado desde parámetros para HDR/EXR
            loader = isEXR ? new hdriLoaders.EXRLoader() : new hdriLoaders.RGBELoader();
        }
        
        // Configurar loader para mejor compatibilidad y menos errores
        if (isHDR) {
            // Usar HalfFloatType en lugar de FloatType para evitar overflow
            loader.setDataType(this.THREE.HalfFloatType);
        }
        // Para JPG no necesita configuración especial
        
        loader.load(
            hdriConfig.primary,
            // Success callback
            (texture) => {
                this.applyHDRIToScene(texture, parameters);
            },
            // Progress callback
            undefined,
            // Error callback
            (error) => {
                console.warn('⚠️ Error cargando HDRI primario:', error);
                this.loadFallbackEnvironment(hdriConfig.fallback, parameters);
            }
        );
    }

    // Aplicar HDRI a la escena con configuración optimizada y sin errores
    applyHDRIToScene(hdriTexture, parameters) {
        // Configurar textura HDRI
        hdriTexture.mapping = this.THREE.EquirectangularReflectionMapping;
        
        // Optimizar configuración para evitar errores Half Float
        hdriTexture.generateMipmaps = false;
        hdriTexture.minFilter = this.THREE.LinearFilter;
        hdriTexture.magFilter = this.THREE.LinearFilter;
        
        // Crear PMREM Generator para environment mapping optimizado
        const renderer = parameters.renderer;
        if (!renderer) {
            return;
        }

        const pmremGenerator = new this.THREE.PMREMGenerator(renderer);
        pmremGenerator.compileEquirectangularShader();
        
        // Generar environment map optimizado con manejo de errores
        let envMap;
        try {
            envMap = pmremGenerator.fromEquirectangular(hdriTexture).texture;
        } catch (error) {
            console.warn('⚠️ Error procesando HDRI, usando environment procedural:', error);
            // Resetear estado de carga cuando falla
            window.globalHDRILoading = false;
            window.globalHDRILoaded = false;
            // Si falla el procesamiento HDRI, usar environment procedural
            pmremGenerator.dispose();
            this.createBasicEnvironment(parameters);
            return;
        }
        
        // Aplicar solo a environment (iluminación y reflejos), NO al fondo
        const scene = parameters.scene;
        if (scene) {
            scene.environment = envMap;
            //scene.background = new this.THREE.Color(0xD0FAE5); // Usar color sólido como fondo
        }

        // Almacenar para uso en materiales
        this.envMap = envMap;

        // Marcar HDRI como cargado globalmente
        this.markHDRIAsLoaded();

        // Limpiar recursos
        hdriTexture.dispose();
        pmremGenerator.dispose();
    }

    // Cargar imagen de respaldo si HDRI falla
    loadFallbackEnvironment(fallbackPath, parameters) {
        // Si no hay fallback path, no hacer nada
        if (!fallbackPath) return;
    
        const textureLoader = new this.THREE.TextureLoader();
        textureLoader.load(
            fallbackPath,
            (texture) => {
                texture.mapping = this.THREE.EquirectangularReflectionMapping;
                const scene = parameters.scene;
                if (scene) {
                    // Solo aplicar a environment, no al fondo
                    scene.environment = texture;
                    // scene.background = texture; // Eliminar para que el fondo no sea HDRI
                }
                this.envMap = texture;
                // Marcar HDRI como cargado globalmente
                this.markHDRIAsLoaded();
            },
            undefined,
            (error) => {
                console.warn('⚠️ Error cargando HDRI fallback:', error);
                // Resetear estado de carga cuando falla el fallback
                window.globalHDRILoading = false;
                window.globalHDRILoaded = false;
            }
        );
    }

    // Cache de materiales para evitar recreación constante
    materialCache = new Map();
    textureCache = new Map();
    
    // Función estática para reiniciar el estado del HDRI global
    static resetGlobalHDRI() {
        window.globalHDRILoading = false;
        window.globalHDRILoaded = false;
        console.log('🔄 Estado global del HDRI reiniciado');
    }
    
    // Función estática para forzar recarga del HDRI
    static forceReloadHDRI() {
        WindowModel.resetGlobalHDRI();
        console.log('🔄 HDRI será recargado en el próximo modelo generado');
    }

    // Limpiar cache de materiales específicamente
    clearMaterialCache() {
        console.log('🧹 Clearing material cache...');
        // Dispose de materiales en cache
        this.materialCache.forEach(materials => {
            Object.values(materials).forEach(material => {
                if (material && material.dispose) {
                    material.dispose();
                }
            });
        });
        this.materialCache.clear();
    }

    // Actualizar colores dinámicamente sin regenerar geometría - OPTIMIZADO
    updateColors(parameters) {
        if (!this.model) return;
        
        // Para debugging: limpiar cache de materiales para forzar recreación
        // TODO: Optimizar esto en producción para no limpiar siempre
        this.clearMaterialCache();
        
        // Crear clave única solo con la textura (si quieres tintar, puedes incluir color)
        const texturePath = parameters.texturePath || '/textures/aluminum/natural/';
        const colorKey = `${texturePath}`;
        console.log('🔑 Cache key:', colorKey);
        // Verificar si ya tenemos los materiales en cache
        if (!this.materialCache.has(colorKey)) {
            console.log('🆕 Creating new materials for:', colorKey);
            const newMaterials = this.createRealisticMaterials(parameters);
            this.materialCache.set(colorKey, newMaterials);
        } else {
            console.log('♻️ Using cached materials for:', colorKey);
        }
        const materials = this.materialCache.get(colorKey);
        
        // Usar cache de elementos para evitar traverse repetitivo
        if (!this.modelElements) {
            this.cacheModelElements();
        }
        
        // Actualizar solo los elementos que necesitan cambio
        this.updateElementMaterials(materials);
    }

    // Cache de elementos del modelo para evitar traverse repetitivo
    cacheModelElements() {
        if (!this.model) return;
        
        this.modelElements = {
            aluminum: [],
            glass: [],
            stainlessSteel: []
        };
        
        this.model.traverse((child) => {
            if (child.isMesh) {
                const childName = child.name || '';
                const parentName = child.parent ? child.parent.name : '';
                const groupName = child.parent && child.parent.parent ? child.parent.parent.name : '';
                
                // Clasificar elementos según su tipo
                if (childName.includes('vidrio') || parentName.includes('glass')) {
                    this.modelElements.glass.push(child);
                } else if (parentName.includes('ruedas') || parentName.includes('seguro') || parentName.includes('tornillos')) {
                    this.modelElements.stainlessSteel.push(child);
                } else {
                    // Todos los perfiles de aluminio por defecto
                    this.modelElements.aluminum.push(child);
                }
            }
        });
    }

    // Actualizar materiales solo en elementos que lo necesitan
    updateElementMaterials(materials) {
        // Actualizar elementos de aluminio (los que cambian de color)
        this.modelElements.aluminum.forEach(element => {
            if (element.material !== materials.aluminum) {
                element.material = materials.aluminum;
            }
        });
        
        // Actualizar vidrio solo si cambió el color del vidrio
        this.modelElements.glass.forEach(element => {
            if (element.material !== materials.glass) {
                element.material = materials.glass;
            }
        });
        
        // Acero inoxidable generalmente no cambia
        this.modelElements.stainlessSteel.forEach(element => {
            if (element.material !== materials.stainlessSteel) {
                element.material = materials.stainlessSteel;
            }
        });
    }

   // Crear materiales realistas con PBR avanzado - OPTIMIZADO CON CACHE
createRealisticMaterials(parameters) {
    // Obtener colores dinámicos desde los parámetros o usar valores por defecto
    const glassColor = parameters.glassColor || '#E0F6FF'; // Vidrio dinámico o azul claro por defecto
    

    // Lógica especial para color blanco
    let texturePath = parameters.texturePath || '/textures/aluminum/natural/';
    let useWhiteTexture = false;
    if (parameters.color && parameters.color.toLowerCase() === 'white') {
        // Usar textura especial para blanco
        texturePath = '/textures/aluminum/white/';
        useWhiteTexture = true;
    }

    // Asegurar que la ruta termine con '/'
    const basePath = texturePath.endsWith('/') ? texturePath : texturePath + '/';

    // Verificar cache de texturas primero
    let textureSet = this.textureCache.get(basePath);

    if (!textureSet) {
        // Solo cargar texturas si no están en cache
        textureSet = this.loadTextureSet(basePath);
        this.textureCache.set(basePath, textureSet);
    }

    const { baseColor, roughness, normal, metalness, displacement } = textureSet;

    // Crear material usando solo la textura base y mapas
    const materialConfig = {
        metalness: 0.8,
        roughness: 0.4,
        envMapIntensity: 0.3,
        clearcoat: 0.1,
        clearcoatRoughness: 0.2,
        opacity: 1.0,
        transparent: false,
    };

    // Usar textura base para el color del aluminio
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

    // Si es blanco, ajustar el color base para que se vea realmente blanco
    if (useWhiteTexture) {
        materialConfig.color = new this.THREE.Color('#F8F8FF'); // Blanco pastel muy claro
        materialConfig.metalness = 0.4;
    }

    // Usar MeshPhysicalMaterial para funciones avanzadas con HDRI
    const aluminumMaterial = new this.THREE.MeshPhysicalMaterial(materialConfig);

    return {
        aluminum: aluminumMaterial,
        
        
        // Vidrio técnico con propiedades físicas reales
        glass: new this.THREE.MeshPhysicalMaterial({
            color: new this.THREE.Color(glassColor),
            transparent: true,
            transmission: 0.95,  // Transmisión de luz realista
            opacity: 0.1,       // Muy transparente
            metalness: 0.0,
            roughness: 0.02,    // Muy suave para reflejos claros
            ior: 1.52,          // Índice de refracción del vidrio
            thickness: 0.01,    // Grosor para cálculo de transmisión
            specularIntensity: 1.0,
            envMapIntensity: 2.0,
            side: this.THREE.DoubleSide
        }),
        
        // Material de acero inoxidable (texturas procedurales)
        stainlessSteel: new this.THREE.MeshPhysicalMaterial({
            color: 0xE8E8E8, // Plata más brillante para diferenciar del aluminio
            metalness: 0.95,    // Alto metalness para acero
            roughness: 0.1,     // Más brillante que el aluminio
            clearcoat: 0.4,
            clearcoatRoughness: 0.02,
            envMapIntensity: 1.5, // Más reflectivo
            normalScale: new this.THREE.Vector2(0.03, 0.03)
        }),

        
        
        // Vidrio interior con menos reflectividad
        innerGlass: new this.THREE.MeshPhysicalMaterial({
            color: new this.THREE.Color(glassColor),
            transparent: true,
            transmission: 0.85,
            opacity: 0.15,
            metalness: 0.0,
            roughness: 0.1,
            ior: 1.45,
            envMapIntensity: 1.0
        })
    };
}

// Cargar conjunto de texturas con cache optimizado
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
        // Error loading textures - usar valores por defecto
    }
    
    return { baseColor, roughness, normal, metalness, displacement };
}

// Limpiar cache para liberar memoria cuando sea necesario
clearCache() {
    // Dispose de texturas en cache
    this.textureCache.forEach(textureSet => {
        Object.values(textureSet).forEach(texture => {
            if (texture && texture.dispose) {
                texture.dispose();
            }
        });
    });
    
    // Dispose de materiales en cache
    this.materialCache.forEach(materials => {
        Object.values(materials).forEach(material => {
            if (material && material.dispose) {
                material.dispose();
            }
        });
    });
    
    // Limpiar caches
    this.textureCache.clear();
    this.materialCache.clear();
    this.modelElements = null;
}

// Crear marco principal con rieles y jambas (materiales reales)
createMainFrame(width, height, depth, frameWidth, materials) {
    const frameGroup = new this.THREE.Group();
    frameGroup.name = 'mainFrame';
    
    // Configuración de perfiles del marco
    const frameProfiles = [
        { 
            create: () => this.createRailProfile(width, frameWidth, depth),
            name: 'railSuperior', 
            position: { y: (height - frameWidth) / 2 },
            addDrainage: false
        },
        { 
            create: () => this.createRailProfile(width, frameWidth, depth),
            name: 'railInferior', 
            position: { y: -(height - frameWidth) / 2 },
            addDrainage: true
        },
        { 
            create: () => this.createJambProfile(frameWidth, height - (frameWidth * 2), depth),
            name: 'jambaIzquierda', 
            position: { x: -(width - frameWidth) / 2 }
        },
        { 
            create: () => this.createJambProfile(frameWidth, height - (frameWidth * 2), depth),
            name: 'jambaDerecha', 
            position: { x: (width - frameWidth) / 2 }
        },
        { 
            create: () => this.createFrameProfile(frameWidth * 0.6, height - (frameWidth * 2), depth),
            name: 'travesanoCentral', 
            position: { x: 0 }
        }
    ];
    
    // Crear y configurar cada perfil
    frameProfiles.forEach(config => {
        const profile = config.create();
        this.applyMaterialToProfile(profile, materials.aluminum);
        profile.name = config.name;
        
        // Aplicar posición
        Object.assign(profile.position, config.position);
        
        // Agregar drenaje si es necesario
        if (config.addDrainage) {
            this.addDrainageHoles(profile, width);
        }
        
        frameGroup.add(profile);
    });
    
    return frameGroup;
}

// Perfiles de marco mejorados con geometrías más realistas
createRailProfile(width, height, depth) {
    const railGroup = new this.THREE.Group();
    
    // Cuerpo principal con bordes redondeados
    const mainBody = new this.THREE.BoxGeometry(width, height, depth);
    const mainMesh = new this.THREE.Mesh(mainBody);
    railGroup.add(mainMesh);
    
    // Canales de deslizamiento con forma más técnica
    const channelWidth = width * 0.85;
    const channelGeometry = new this.THREE.BoxGeometry(channelWidth, height * 0.25, depth * 0.5);
    
    // Canal principal central
    const mainChannel = new this.THREE.Mesh(channelGeometry);
    mainChannel.position.z = 0;
    mainChannel.position.y = -height * 0.15;
    railGroup.add(mainChannel);
    
    // Guías laterales para ruedas
    const guideGeometry = new this.THREE.BoxGeometry(channelWidth, height * 0.15, depth * 0.3);
    const leftGuide = new this.THREE.Mesh(guideGeometry);
    leftGuide.position.z = depth * 0.25;
    leftGuide.position.y = -height * 0.1;
    railGroup.add(leftGuide);
    
    const rightGuide = new this.THREE.Mesh(guideGeometry);
    rightGuide.position.z = -depth * 0.25;
    rightGuide.position.y = -height * 0.1;
    railGroup.add(rightGuide);
    
    return railGroup;
}

// Jamba con detalles de instalación
createJambProfile(width, height, depth) {
    const jambGroup = new this.THREE.Group();
    
    // Cuerpo principal
    const mainGeometry = new this.THREE.BoxGeometry(width, height, depth);
    const mainMesh = new this.THREE.Mesh(mainGeometry);
    jambGroup.add(mainMesh);
    
    // Ranura para sellado principal
    const grooveGeometry = new this.THREE.BoxGeometry(width * 0.3, height * 0.98, depth * 0.4);
    const groove = new this.THREE.Mesh(grooveGeometry);
    groove.position.x = width * 0.15;
    groove.position.z = depth * 0.15;
    jambGroup.add(groove);
    
    // Canal de drenaje de agua
    const drainGeometry = new this.THREE.BoxGeometry(width * 0.2, height * 0.1, depth * 0.6);
    const drain = new this.THREE.Mesh(drainGeometry);
    drain.position.x = -width * 0.1;
    drain.position.z = -depth * 0.1;
    jambGroup.add(drain);
    
    return jambGroup;
}

// Perfil de marco con múltiples cámaras
createFrameProfile(width, height, depth) {
    const profileGroup = new this.THREE.Group();
    
    // Perfil principal con refuerzos
    const mainProfile = new this.THREE.Mesh(
        new this.THREE.BoxGeometry(width, height, depth)
    );
    profileGroup.add(mainProfile);
    
    // Cámara de aislamiento térmico
    const insulationGeometry = new this.THREE.BoxGeometry(width * 0.7, height * 0.8, depth * 0.6);
    const insulation = new this.THREE.Mesh(insulationGeometry);
    insulation.position.z = depth * 0.1;
    profileGroup.add(insulation);
    
    // Guías para vidrio (interior y exterior)
    const glassGuideGeometry = new this.THREE.BoxGeometry(width * 0.4, height * 0.9, depth * 0.2);
    
    const innerGuide = new this.THREE.Mesh(glassGuideGeometry);
    innerGuide.position.z = depth * 0.35;
    profileGroup.add(innerGuide);
    
    const outerGuide = new this.THREE.Mesh(glassGuideGeometry);
    outerGuide.position.z = -depth * 0.35;
    profileGroup.add(outerGuide);
    
    return profileGroup;
}

// Aplicar material a todos los elementos de un perfil
applyMaterialToProfile(profile, material) {
    profile.traverse((child) => {
        if (child.isMesh) {
            child.material = material;
            child.castShadow = true;
            child.receiveShadow = true;
        }
    });
}
// Agregar orificios de drenaje
addDrainageHoles(profile, width) {
    const holeCount = Math.floor(width / 0.3);
    const spacing = width / (holeCount + 1);
    
    for (let i = 1; i <= holeCount; i++) {
        const hole = new this.THREE.Mesh(
            new this.THREE.CylinderGeometry(0.003, 0.003, 0.02, 8),
            new this.THREE.MeshBasicMaterial({ color: 0x000000 })
        );
        hole.position.set(-width/2 + i * spacing, -0.02, 0);
        hole.rotation.x = Math.PI / 2;
        profile.add(hole);
    }
}

// Crear hojas correderas con componentes específicos
createSlidingPanelsWithRealComponents(width, height, depth, frameWidth, materials, parentGroup) {
    const panelWidth = (width - frameWidth * 2) / 2; // Cada hoja ocupa la mitad del espacio interior
    const panelHeight = height - frameWidth * 2;
    const halfWidth = (width - frameWidth * 2) / 4; // Un cuarto del ancho interior desde el centro
    
    // HOJA 1 (IZQUIERDA) - Posición inicial: lado izquierdo dentro del marco
    const panel1 = this.createPanelWithRealProfiles(panelWidth, panelHeight, depth * 0.8, frameWidth * 0.8, materials);
    panel1.position.x = -halfWidth; // Lado izquierdo dentro del marco
    panel1.position.z = depth / 8; // Plano frontal
    panel1.name = 'slidingPanel1'; // Nombre directo
    // Inicializar userData inmediatamente
    panel1.userData = {
        originalX: -halfWidth,
        originalZ: depth / 8,
        isOpen: false,
        slideDistance: width * 0.25,
        side: 'left',
        currentDisplacement: 0 // Para tracking de ruedas
    };
    parentGroup.add(panel1);
    
    // HOJA 2 (DERECHA) - Posición inicial: lado derecho dentro del marco
    const panel2 = this.createPanelWithRealProfiles(panelWidth, panelHeight, depth * 0.8, frameWidth * 0.8, materials);
    panel2.position.x = halfWidth; // Lado derecho dentro del marco
    panel2.position.z = -depth / 8; // Plano trasero (para superposición)
    panel2.name = 'slidingPanel2'; // Nombre directo
    // Inicializar userData inmediatamente
    panel2.userData = {
        originalX: halfWidth,
        originalZ: -depth / 8,
        isOpen: false,
        slideDistance: width * 0.25,
        side: 'right',
        currentDisplacement: 0 // Para tracking de ruedas
    };
    parentGroup.add(panel2);
    
    return { panel1, panel2 };
}

// Crear hoja individual con perfiles horizontales y verticales específicos
createPanelWithRealProfiles(width, height, depth, frameWidth, materials) {
    const panelGroup = new this.THREE.Group();
    
    // PERFIL HORIZONTAL SUPERIOR (material: Horizontal Superior)
    const topHorizontal = this.createHorizontalProfile(width, frameWidth, depth);
    this.applyMaterialToProfile(topHorizontal, materials.aluminum);
    topHorizontal.name = 'horizontalSuperior';
    topHorizontal.position.y = (height - frameWidth) / 2;
    panelGroup.add(topHorizontal);
    
    // PERFIL HORIZONTAL INFERIOR (material: Horizontal Inferior)
    const bottomHorizontal = this.createHorizontalProfile(width, frameWidth, depth);
    this.applyMaterialToProfile(bottomHorizontal, materials.aluminum);
    bottomHorizontal.name = 'horizontalInferior';
    bottomHorizontal.position.y = -(height - frameWidth) / 2;
    panelGroup.add(bottomHorizontal);
    
    // PERFIL VERTICAL IZQUIERDO (material: Vertical Lateral)
    const leftVertical = this.createVerticalProfile(frameWidth, height - (frameWidth * 2), depth);
    this.applyMaterialToProfile(leftVertical, materials.aluminum);
    leftVertical.name = 'verticalIzquierdo';
    leftVertical.position.x = -(width - frameWidth * 1.5) / 2; // Ajustar posición para mejor visibilidad
    panelGroup.add(leftVertical);
    
    // PERFIL VERTICAL DERECHO (material: Vertical Lateral)
    const rightVertical = this.createVerticalProfile(frameWidth, height - (frameWidth * 2), depth);
    this.applyMaterialToProfile(rightVertical, materials.aluminum);
    rightVertical.name = 'verticalDerecho';
    rightVertical.position.x = (width - frameWidth * 1.5) / 2; // Ajustar posición para mejor visibilidad
    panelGroup.add(rightVertical);
    
    // VIDRIO (material: Vidrio)
    const glass = this.createPanelGlass(width, height, frameWidth, materials.glass);
    glass.name = 'vidrio';
    panelGroup.add(glass);
    
    return panelGroup;
}

// Crear perfil HORIZONTAL específico (superior/inferior de hojas)
createHorizontalProfile(width, height, depth) {
    const horizontalGroup = new this.THREE.Group();
    
    // Cuerpo principal del perfil horizontal
    const mainGeometry = new this.THREE.BoxGeometry(width, height, depth);
    const mainMesh = new this.THREE.Mesh(mainGeometry);
    horizontalGroup.add(mainMesh);
    
    // Ranura central para vidrio (característico de horizontales)
    const glassGrooveGeometry = new this.THREE.BoxGeometry(width * 0.9, height * 0.4, depth * 0.2);
    const glassGroove = new this.THREE.Mesh(glassGrooveGeometry);
    glassGroove.position.y = 0;
    horizontalGroup.add(glassGroove);
    
    return horizontalGroup;
}

// Crear perfil VERTICAL específico (laterales de hojas)
createVerticalProfile(width, height, depth) {
    const verticalGroup = new this.THREE.Group();
    
    // Cuerpo principal del perfil vertical - más ancho para mejor visibilidad
    const mainGeometry = new this.THREE.BoxGeometry(width * 1.5, height, depth);
    const mainMesh = new this.THREE.Mesh(mainGeometry);
    verticalGroup.add(mainMesh);
    
    // Ranura para vidrio y sellado
    const glassGrooveGeometry = new this.THREE.BoxGeometry(width * 0.4, height * 0.9, depth * 0.2);
    const glassGroove = new this.THREE.Mesh(glassGrooveGeometry);
    glassGroove.position.x = 0;
    verticalGroup.add(glassGroove);
    
    // Cámara de drenaje (característica de verticales)
    const drainChamberGeometry = new this.THREE.BoxGeometry(width * 0.3, height * 0.1, depth * 0.8);
    const drainChamber = new this.THREE.Mesh(drainChamberGeometry);
    drainChamber.position.y = -height * 0.4;
    verticalGroup.add(drainChamber);
    
    return verticalGroup;
}

// Crear vidrio de panel específico
createPanelGlass(width, height, frameWidth, glassMaterial) {
    // Hacer el vidrio más pequeño para que no oculte los perfiles verticales
    const glassWidth = width - frameWidth * 4; // Más margen para los perfiles verticales
    const glassHeight = height - frameWidth * 4; // Más margen para los perfiles horizontales
    
    const glassGeometry = new this.THREE.PlaneGeometry(glassWidth, glassHeight);
    const glass = new this.THREE.Mesh(glassGeometry, glassMaterial);
    
    return glass;
}

// Herrajes mejorados con más detalle
createRealHardware(width, height, depth, materials) {
    const hardwareGroup = new this.THREE.Group();
    hardwareGroup.name = 'realHardware';
    
    // Sistema de ruedas para cálculo de costos (no visible)
    const wheelsGroup = this.createAdvancedWheelSystem(width, height, depth);
    this.applyMaterialToProfile(wheelsGroup, materials.stainlessSteel);
    
    // Mecanismo de bloqueo premium
    const lockGroup = this.createPremiumLockSystem(width, height, depth);
    this.applyMaterialToProfile(lockGroup, materials.stainlessSteel);
    hardwareGroup.add(lockGroup);
    
    // Sistema de tornillería visible
    const screwsGroup = this.createRealisticScrews(width, height, depth);
    this.applyMaterialToProfile(screwsGroup, materials.stainlessSteel);
    hardwareGroup.add(screwsGroup);
    
    return hardwareGroup;
}

// Sistema de ruedas avanzado
createAdvancedWheelSystem(width, height, depth) {
    const wheelsGroup = new this.THREE.Group();
    wheelsGroup.name = 'advancedWheels';
    
    // Ruedas con rodamientos
    const createWheelAssembly = (x, y, z, rotation) => {
        const wheelAssembly = new this.THREE.Group();
        
        // Eje
        const axleGeometry = new this.THREE.CylinderGeometry(0.005, 0.005, 0.02, 8);
        const axle = new this.THREE.Mesh(axleGeometry);
        axle.rotation.z = Math.PI / 2;
        wheelAssembly.add(axle);
        
        // Rueda principal
        const wheelGeometry = new this.THREE.CylinderGeometry(0.015, 0.015, 0.012, 16);
        const wheel = new this.THREE.Mesh(wheelGeometry);
        wheel.rotation.z = Math.PI / 2;
        wheelAssembly.add(wheel);
        
        // Rodamiento
        const bearingGeometry = new this.THREE.TorusGeometry(0.008, 0.002, 8, 16);
        const bearing = new this.THREE.Mesh(bearingGeometry);
        bearing.rotation.x = Math.PI / 2;
        wheelAssembly.add(bearing);
        
        wheelAssembly.position.set(x, y, z);
        wheelAssembly.rotation.y = rotation;
        
        return wheelAssembly;
    };
    
    // Posiciones de las ruedas (2 por hoja - solo superiores)
    const wheelPositions = [
        // Hoja izquierda - solo ruedas superiores
        { x: -width * 0.35, y: height * 0.42, z: depth * 0.3, rot: Math.PI },
        { x: -width * 0.15, y: height * 0.42, z: depth * 0.3, rot: Math.PI },
        // Hoja derecha - solo ruedas superiores
        { x: width * 0.15, y: height * 0.42, z: -depth * 0.3, rot: Math.PI },
        { x: width * 0.35, y: height * 0.42, z: -depth * 0.3, rot: Math.PI }
    ];
    
    wheelPositions.forEach((pos, index) => {
        const wheel = createWheelAssembly(pos.x, pos.y, pos.z, pos.rot);
        wheel.name = `wheelAssembly${index + 1}`;
        wheelsGroup.add(wheel);
    });
    
    return wheelsGroup;
}

// Sistema de bloqueo premium
createPremiumLockSystem(width, height, depth) {
    const lockGroup = new this.THREE.Group();
    lockGroup.name = 'premiumLock';

    const material = new this.THREE.MeshStandardMaterial({ 
        color: 0x1a1a1a, 
        metalness: 0.6, 
        roughness: 0.3 
    });

    // --- CUERPO PRINCIPAL (base rectangular) ---
    const baseGeometry = new this.THREE.BoxGeometry(0.03, 0.1, 0.01);
    const base = new this.THREE.Mesh(baseGeometry, material);
    base.position.set(0, 0, depth / 2 + 0.005);
    lockGroup.add(base);

    // --- CILINDRO SUPERIOR (parte redonda del seguro) ---
    const cylinderGeometry = new this.THREE.CylinderGeometry(0.018, 0.018, 0.025, 32);
    const cylinder = new this.THREE.Mesh(cylinderGeometry, material);
    cylinder.rotation.x = Math.PI / 2;
    cylinder.position.set(0, 0.015, depth / 2 + 0.02);
    lockGroup.add(cylinder);

    // --- MANIJA ---
    const handleGeometry = new this.THREE.BoxGeometry(0.04, 0.01, 0.008);
    const handle = new this.THREE.Mesh(handleGeometry, material);
    handle.position.set(0.03, 0.015, depth / 2 + 0.02);
    lockGroup.add(handle);

    // --- PUNTOS DE ATORNILLADO ---
    const screwGeometry = new this.THREE.CylinderGeometry(0.003, 0.003, 0.002, 16);
    for (let i of [-0.02, 0.02]) {
        const screw = new this.THREE.Mesh(
            screwGeometry,
            new this.THREE.MeshStandardMaterial({ color: 0x111111 })
        );
        screw.rotation.x = Math.PI / 2;
        screw.position.set(i, -0.035, depth / 2 + 0.01);
        lockGroup.add(screw);
    }

    return lockGroup;
}


// Tornillos realistas con cabeza
createRealisticScrews(width, height, depth) {
    const screwsGroup = new this.THREE.Group();
    screwsGroup.name = 'realisticScrews';
    
    const createScrew = (x, y, z) => {
        const screwGroup = new this.THREE.Group();
        
        // Cabeza del tornillo
        const headGeometry = new this.THREE.CylinderGeometry(0.004, 0.004, 0.002, 6);
        const head = new this.THREE.Mesh(headGeometry);
        screwGroup.add(head);
        
        // Cuerpo del tornillo
        const bodyGeometry = new this.THREE.CylinderGeometry(0.002, 0.002, 0.008, 6);
        const body = new this.THREE.Mesh(bodyGeometry);
        body.position.y = -0.005;
        screwGroup.add(body);
        
        screwGroup.position.set(x, y, z);
        return screwGroup;
    };
    
    // Posiciones de tornillos estratégicas
    const screwPositions = [
        // Esquinas marco principal
        { x: -width * 0.48, y: height * 0.48, z: 0 },
        { x: width * 0.48, y: height * 0.48, z: 0 },
        { x: -width * 0.48, y: -height * 0.48, z: 0 },
        { x: width * 0.48, y: -height * 0.48, z: 0 },
        // Refuerzos centrales
        { x: 0, y: height * 0.4, z: 0 },
        { x: 0, y: -height * 0.4, z: 0 },
        // Uniones de hojas
        { x: -width * 0.25, y: 0, z: depth * 0.35 },
        { x: width * 0.25, y: 0, z: -depth * 0.35 }
    ];
    
    screwPositions.forEach((pos, index) => {
        const screw = createScrew(pos.x, pos.y, pos.z);
        screw.name = `screw${index + 1}`;
        screwsGroup.add(screw);
    });
    
    return screwsGroup;
}

// Sistema de sellado mejorado - Solo para cálculo de costos (ambos ocultos)
createSealing(width, height, depth, materials) {
    const sealingGroup = new this.THREE.Group();
    sealingGroup.name = 'advancedSealing';
    
    // Crear elementos de sellado para cálculo de costos
    const felpaGroup = this.createAdvancedFelpa(width, height, depth);
    const rubberGroup = this.createAdvancedRubberSeals(width, height, depth);
    
    return sealingGroup;
}

// Sellado de felpa avanzado - Solo para cálculo de costos (no visible)
createAdvancedFelpa(width, height, depth) {
    const felpaGroup = new this.THREE.Group();
    felpaGroup.name = 'advancedFelpa';
    
    const felpaGeometry = new this.THREE.BoxGeometry(0.006, 0.004, 0.015);
    
    // Patrón de instalación perimetral completo
    const segments = [
        // Superior e inferior
        { pos: [0, height * 0.495, 0], scale: [width * 0.96, 1, 1], count: Math.floor(width / 0.1) },
        { pos: [0, -height * 0.495, 0], scale: [width * 0.96, 1, 1], count: Math.floor(width / 0.1) },
        // Laterales
        { pos: [-width * 0.495, 0, 0], scale: [height * 0.96, 1, 1], rotation: [0, 0, Math.PI / 2], count: Math.floor(height / 0.1) },
        { pos: [width * 0.495, 0, 0], scale: [height * 0.96, 1, 1], rotation: [0, 0, Math.PI / 2], count: Math.floor(height / 0.1) }
    ];
    
    segments.forEach(segment => {
        for (let i = 0; i < segment.count; i++) {
            const felpa = new this.THREE.Mesh(felpaGeometry);
            const t = (i / (segment.count - 1)) - 0.5;
            
            if (segment.pos[0] === 0) { // Horizontal
                felpa.position.set(t * segment.scale[0], segment.pos[1], segment.pos[2]);
            } else { // Vertical
                felpa.position.set(segment.pos[0], t * segment.scale[0], segment.pos[2]);
            }
            
            if (segment.rotation) {
                felpa.rotation.set(segment.rotation[0], segment.rotation[1], segment.rotation[2]);
            }
            
            felpaGroup.add(felpa);
        }
    });
    
    return felpaGroup;
}

// Sellados de goma avanzados - Solo para cálculo de costos (no visible)
createAdvancedRubberSeals(width, height, depth) {
    const rubberGroup = new this.THREE.Group();
    rubberGroup.name = 'advancedRubber';
    
    // Sellado principal de encuentro
    const mainSealGeometry = new this.THREE.BoxGeometry(0.01, height * 0.9, depth * 0.3);
    const mainSeal = new this.THREE.Mesh(mainSealGeometry);
    mainSeal.position.set(0, 0, depth * 0.1);
    rubberGroup.add(mainSeal);
    
    // Sellados laterales para hojas
    const sideSealGeometry = new this.THREE.BoxGeometry(0.008, height * 0.85, depth * 0.2);
    
    const leftSeal = new this.THREE.Mesh(sideSealGeometry);
    leftSeal.position.set(-width * 0.25, 0, depth * 0.25);
    rubberGroup.add(leftSeal);
    
    const rightSeal = new this.THREE.Mesh(sideSealGeometry);
    rightSeal.position.set(width * 0.25, 0, -depth * 0.25);
    rubberGroup.add(rightSeal);
    
    return rubberGroup;
}

    // Configurar funcionalidad de deslizamiento mejorada
    setupSlidingFunctionality(group, width, height, depth, frameWidth) {
        // Obtener ambas hojas (acceso directo desde el grupo principal)
        const panel1 = group.getObjectByName('slidingPanel1');
        const panel2 = group.getObjectByName('slidingPanel2');
        
        // Función para deslizar hoja específica - LÓGICA CORRECTA: UNA ENCIMA DE OTRA
        const windowDepth = depth; // Solo necesaria para cálculos de profundidad
        
        group.userData.slidePanel = (panelNumber, action = 'toggle') => {
            
            // Acceso directo a paneles
            const panel = group.getObjectByName(`slidingPanel${panelNumber}`);
            const otherPanel = group.getObjectByName(`slidingPanel${panelNumber === '1' ? '2' : '1'}`);
            
            if (!panel) {
                return;
            }
            
            const duration = 800;
            let targetX, targetZ;
            
            if (action === 'toggle') {
                if (panel.userData.isOpen) {
                    // CERRAR: volver a posición original
                    targetX = panel.userData.originalX;
                    targetZ = panel.userData.originalZ;
                } else {
                    // ABRIR: mover sobre la otra hoja
                    if (panelNumber === '1') {
                        // Hoja 1 (izquierda) se desliza hacia la derecha encima de hoja 2
                        targetX = otherPanel ? otherPanel.userData.originalX : panel.userData.originalX + panel.userData.slideDistance;
                        targetZ = windowDepth / 6; // Plano frontal para estar encima
                    } else {
                        // Hoja 2 (derecha) se desliza hacia la izquierda encima de hoja 1
                        targetX = otherPanel ? otherPanel.userData.originalX : panel.userData.originalX - panel.userData.slideDistance;
                        targetZ = windowDepth / 6; // Plano frontal para estar encima
                    }
                }
            }
            
            // Animar tanto X como Z
            this.animateSlidingWithDepth(panel, targetX, targetZ, duration);
            panel.userData.isOpen = !panel.userData.isOpen;
        };
        
        // Función para abrir/cerrar toda la ventana
        group.userData.slideWindow = (action = 'toggle') => {
            group.userData.slidePanel('1', action);
            setTimeout(() => {
                group.userData.slidePanel('2', action);
            }, 200); // Retraso de 200ms para efecto escalonado
        };
        
        // Función para click general en la ventana
        group.userData.onClick = () => {
            group.userData.slideWindow('toggle');
        };
        
        // Función para click en hoja específica
        group.userData.onPanelClick = (panelNumber) => {
            group.userData.slidePanel(panelNumber, 'toggle');
        };
        
        // Función para resetear posiciones
        group.userData.resetPanels = () => {
            ['slidingPanel1', 'slidingPanel2'].forEach(panelName => {
                const panel = group.getObjectByName(panelName);
                if (panel && panel.userData) {
                    panel.position.x = panel.userData.originalX;
                    panel.position.z = panel.userData.originalZ;
                    panel.userData.isOpen = false;
                    panel.userData.currentDisplacement = 0;
                }
            });
        };
        
        // Estado de la ventana
        group.userData.getWindowState = () => {
            const state = {
                panel1Open: panel1 ? panel1.userData.isOpen : false,
                panel2Open: panel2 ? panel2.userData.isOpen : false
            };
            state.fullyOpen = state.panel1Open && state.panel2Open;
            state.partiallyClosed = state.panel1Open || state.panel2Open;
            state.fullyClosed = !state.panel1Open && !state.panel2Open;
            return state;
        };
    }

    // Animación de deslizamiento con profundidad (X y Z)
    animateSlidingWithDepth(panel, targetX, targetZ, duration) {
        if (!panel) {
            return;
        }
        
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
            
            // Easing más suave (ease-out-cubic)
            const easeProgress = 1 - Math.pow(1 - progress, 3);
            
            panel.position.x = startX + distanceX * easeProgress;
            panel.position.z = startZ + distanceZ * easeProgress;
            
            // Almacenar desplazamiento actual para la siguiente frame
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

    // Hacer la ventana interactiva
    makeInteractive(group) {
        // Agregar eventos de mouse a los paneles
        const panel1 = group.getObjectByName('slidingPanel1');
        const panel2 = group.getObjectByName('slidingPanel2');
        
        if (panel1) {
            panel1.userData.onPointerDown = (event) => {
                event.stopPropagation();
                group.userData.onPanelClick('1');
            };
        }
        
        if (panel2) {
            panel2.userData.onPointerDown = (event) => {
                event.stopPropagation();
                group.userData.onPanelClick('2');
            };
        }
        
        // Agregar controles de teclado
        group.userData.handleKeyPress = (event) => {
            switch(event.key) {
                case '1':
                    group.userData.slidePanel('1', 'toggle');
                    break;
                case '2':
                    group.userData.slidePanel('2', 'toggle');
                    break;
                case ' ': // Barra espaciadora
                    event.preventDefault();
                    group.userData.slideWindow('toggle');
                    break;
                case 'r': // Reset
                case 'R':
                    group.userData.resetPanels();
                    break;
            }
        };
    }
    
    // Configurar sombras
    setupShadows(group) {
        group.traverse((child) => {
            if (child.isMesh) {
                child.castShadow = true;
                child.receiveShadow = true;
            }
        });
    }

    // Destruir modelo y limpiar memoria
    destroy() {
        this.clearCache();
        if (this.model) {
            this.model.traverse((child) => {
                if (child.isMesh) {
                    if (child.geometry) child.geometry.dispose();
                    if (child.material) {
                        if (child.material.map) child.material.map.dispose();
                        if (child.material.normalMap) child.material.normalMap.dispose();
                        if (child.material.roughnessMap) child.material.roughnessMap.dispose();
                        if (child.material.displacementMap) child.material.displacementMap.dispose();
                        child.material.dispose();
                    }
                }
            });
            this.model = null;
        }
        this.modelElements = null;
    }
}

// Función global para verificar estado del HDRI
window.getHDRIStatus = () => {
    return {
        isLoaded: window.globalHDRILoaded === true,
        isLoading: window.globalHDRILoading === true,
        status: window.globalHDRILoaded ? 'loaded' : 
               window.globalHDRILoading ? 'loading' : 'not-loaded'
    };
};

// Función global para resetear HDRI si hay problemas
window.resetHDRI = () => {
    WindowModel.resetGlobalHDRI();
};

// Exposición global para compatibilidad con scripts
if (typeof window !== 'undefined') {
    window.WindowModel = WindowModel;
    
    // Inicializar estado global del HDRI
    if (typeof window.globalHDRILoaded === 'undefined') {
        window.globalHDRILoaded = false;
        window.globalHDRILoading = false;
    }
}
