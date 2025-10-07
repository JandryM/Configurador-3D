// Modelo 3D realista para ventanas de aluminio
export class WindowModel {
    constructor(THREE) {
        this.THREE = THREE;
    }

    // Generar ventana completa
    generate(parameters) {
        const group = new this.THREE.Group();
        const { width, height, frameWidth } = parameters;
        
        // Profundidad fija de 1 metro (no modificable, no afecta precio)
        const depth = 0.12;

        // Materiales realistas
        const materials = this.createRealisticMaterials(parameters);
        
        // Componentes específicos según materiales reales
        
        // 1. Marco principal (Rieles y Jambas)
        const mainFrame = this.createMainFrame(width, height, depth, frameWidth, materials);
        group.add(mainFrame);
        
        // 2. Hojas correderas (2 hojas con perfiles horizontales y verticales) - AGREGADAS DIRECTAMENTE
        const slidingPanels = this.createSlidingPanelsWithRealComponents(width, height, depth, frameWidth, materials, group);
        
        // 3. Herrajes específicos (ruedas, seguro, tornillos)
        const realHardware = this.createRealHardware(width, height, depth, materials);
        group.add(realHardware);
        
        // 4. Sellado (felpa y caucho)
        const sealing = this.createSealing(width, height, depth, materials);
        group.add(sealing);

        // Configurar sombras
        this.setupShadows(group);

        // Debug: Listar todos los objetos creados
        group.children.forEach((child, index) => {
            if (child.children.length > 0) {
                child.children.forEach((subChild, subIndex) => {
                });
            }
        });

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

        // Agregar función de prueba global para debugging
        window.testWindowSlide = () => {
            
            const p1 = group.getObjectByName('slidingPanel1');
            const p2 = group.getObjectByName('slidingPanel2');
            
            if (group.userData.slidePanel) {
                group.userData.slidePanel('1', 'toggle');
            } else {
            }
        };
        
        // Función de prueba específica para ruedas
        window.testWheelAnimation = () => {
            const panel1 = group.getObjectByName('slidingPanel1');
            
            if (panel1 && group.userData.animateWheels) {
                
                // Animar ruedas manualmente para prueba
                let displacement = 0;
                const maxDisplacement = 0.5;
                const animateTest = () => {
                    displacement += 0.02;
                    group.userData.animateWheels(panel1, displacement);
                    
                    if (displacement < maxDisplacement) {
                        requestAnimationFrame(animateTest);
                    } else {
                        // Resetear ruedas
                        setTimeout(() => {
                            group.userData.resetWheels(panel1, '1');
                        }, 1000);
                    }
                };
                animateTest();
            } else {
            }
        };

        return group;
    }

    // Actualizar colores dinámicamente sin regenerar geometría
    updateColors(parameters) {
        if (!this.model) return;
        
        const newMaterials = this.createRealisticMaterials(parameters);
        
        this.model.traverse((child) => {
            if (child.isMesh) {
                const childName = child.name || '';
                const parentName = child.parent ? child.parent.name : '';
                const groupName = child.parent && child.parent.parent ? child.parent.parent.name : '';
                
                // Asignar materiales según componentes específicos
                if (childName.includes('vidrio') || parentName.includes('glass')) {
                    child.material = newMaterials.glass;
                } else if (parentName.includes('felpa') || childName.includes('felpa')) {
                    // Mantener material específico de felpa
                    return;
                } else if (parentName.includes('caucho') || childName.includes('caucho')) {
                    // Mantener material específico de caucho
                    return;
                } else if (parentName.includes('ruedas') || parentName.includes('seguro') || parentName.includes('tornillos')) {
                    child.material = newMaterials.stainlessSteel;
                } else if (parentName.includes('rail') || parentName.includes('jamba') || 
                          parentName.includes('horizontal') || parentName.includes('vertical') ||
                          parentName.includes('mainFrame') || groupName.includes('slidingPanel') ||
                          childName === 'frameProfile') {
                    // Todos los perfiles de aluminio
                    child.material = newMaterials.aluminum;
                } else {
                    // Material por defecto para componentes no identificados
                    child.material = newMaterials.aluminum;
                }
                
                child.material.needsUpdate = true;
            }
        });
    }

   // Crear materiales realistas con PBR avanzado
createRealisticMaterials(parameters) {
    const frameColor = parameters.frameColor || parameters.color || '#C0C0C0'; // Plata estándar de aluminio
    const glassColor = parameters.glassColor || '#E0F6FF';
    
    // Texturas procedurales para mayor realismo
    const createAnodizedTexture = (baseColor) => {
        const canvas = document.createElement('canvas');
        canvas.width = 512;
        canvas.height = 512;
        const ctx = canvas.getContext('2d');
        
        // Base color
        ctx.fillStyle = baseColor;
        ctx.fillRect(0, 0, 512, 512);
        
        // Patrón de anodizado con ruido sutil
        for (let i = 0; i < 10000; i++) {
            const x = Math.random() * 512;
            const y = Math.random() * 512;
            const size = Math.random() * 3 + 1;
            const alpha = Math.random() * 0.1;
            
            ctx.fillStyle = `rgba(255, 255, 255, ${alpha})`;
            ctx.fillRect(x, y, size, size);
        }
        
        const texture = new this.THREE.CanvasTexture(canvas);
        texture.wrapS = this.THREE.RepeatWrapping;
        texture.wrapT = this.THREE.RepeatWrapping;
        texture.repeat.set(4, 4);
        return texture;
    };

    return {
        // Aluminio anodizado estándar plata con textura procedural
        aluminum: new this.THREE.MeshPhysicalMaterial({
            color: new this.THREE.Color(frameColor),
            metalness: 0.7,  // Mayor metalness para aspecto más metálico
            roughness: 0.2,  // Superficie más lisa y brillante
            clearcoat: 0.6,  // Capa de anodizado
            clearcoatRoughness: 0.05,
            normalScale: new this.THREE.Vector2(0.05, 0.05),
            envMapIntensity: 1.2, // Mayor reflectividad
            map: createAnodizedTexture(frameColor) // Textura procedural de anodizado activada
        }),
        
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
        
        // Acero inoxidable para herrajes (más brillante que aluminio)
        stainlessSteel: new this.THREE.MeshPhysicalMaterial({
            color: 0xE8E8E8, // Plata más brillante para diferenciar del aluminio
            metalness: 0.95,    // Alto metalness para acero
            roughness: 0.1,     // Más brillante que el aluminio
            clearcoat: 0.4,
            clearcoatRoughness: 0.02,
            envMapIntensity: 1.5, // Más reflectivo
            normalScale: new this.THREE.Vector2(0.03, 0.03)
        }),
        
        // Goma de sellado con textura porosa
        rubber: new this.THREE.MeshPhysicalMaterial({
            color: 0x1A1A1A,
            metalness: 0.0,
            roughness: 0.9,     // Muy rugoso para material no reflectante
            transmission: 0.0,
            opacity: 1.0,
            envMapIntensity: 0.1,
            normalScale: new this.THREE.Vector2(0.3, 0.3)
        }),
        
        // Felpa para sellado - material más suave
        felt: new this.THREE.MeshPhysicalMaterial({
            color: 0x2A2A2A,
            metalness: 0.0,
            roughness: 0.95,    // Extremadamente mate
            sheen: 0.1,         // Ligero efecto de felpa
            envMapIntensity: 0.05
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

// Crear marco principal con rieles y jambas (materiales reales)
createMainFrame(width, height, depth, frameWidth, materials) {
    const frameGroup = new this.THREE.Group();
    frameGroup.name = 'mainFrame';
    
    // RIEL SUPERIOR (material: Riel Superior/Inferior)
    const topRail = this.createRailProfile(width, frameWidth, depth);
    this.applyMaterialToProfile(topRail, materials.aluminum);
    topRail.name = 'railSuperior';
    topRail.position.y = (height - frameWidth) / 2;
    frameGroup.add(topRail);
    
    // RIEL INFERIOR (material: Riel Superior/Inferior)
    const bottomRail = this.createRailProfile(width, frameWidth, depth);
    this.applyMaterialToProfile(bottomRail, materials.aluminum);
    bottomRail.name = 'railInferior';
    bottomRail.position.y = -(height - frameWidth) / 2;
    this.addDrainageHoles(bottomRail, width);
    frameGroup.add(bottomRail);
    
    // JAMBA IZQUIERDA (material: Jamba Lateral)
    const leftJamb = this.createJambProfile(frameWidth, height - (frameWidth * 2), depth);
    this.applyMaterialToProfile(leftJamb, materials.aluminum);
    leftJamb.name = 'jambaIzquierda';
    leftJamb.position.x = -(width - frameWidth) / 2;
    frameGroup.add(leftJamb);
    
    // JAMBA DERECHA (material: Jamba Lateral)
    const rightJamb = this.createJambProfile(frameWidth, height - (frameWidth * 2), depth);
    this.applyMaterialToProfile(rightJamb, materials.aluminum);
    rightJamb.name = 'jambaDerecha';
    rightJamb.position.x = (width - frameWidth) / 2;
    frameGroup.add(rightJamb);
    
    // Travesaño central SIEMPRE presente (ventana corredera 2 hojas)
    const verticalMullion = this.createFrameProfile(frameWidth * 0.6, height - (frameWidth * 2), depth);
    this.applyMaterialToProfile(verticalMullion, materials.aluminum);
    verticalMullion.name = 'travesanoCentral';
    verticalMullion.position.x = 0;
    frameGroup.add(verticalMullion);
    
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

// Calcular configuración de paneles - SIEMPRE 2 hojas (corredera)
calculatePanelConfiguration(width, height) {
    const config = { panels: [] };
    
    // SIEMPRE generar exactamente 2 paneles (hojas) como ventana corredera
    const travesanoWidth = 0.03; // Ancho del travesaño central
    const panelWidth = (width - travesanoWidth) / 2 - 0.06; // Dividir en 2 con margen
    const panelHeight = height - 0.1; // Altura con margen superior e inferior
    
    // Panel izquierdo (hoja fija o móvil)
    config.panels.push({
        x: -(travesanoWidth/2 + panelWidth/2 + 0.03), 
        y: 0, 
        width: panelWidth, 
        height: panelHeight,
        type: 'left'
    });
    
    // Panel derecho (hoja móvil)
    config.panels.push({
        x: (travesanoWidth/2 + panelWidth/2 + 0.03), 
        y: 0, 
        width: panelWidth, 
        height: panelHeight,
        type: 'right'
    });
    
    return config;
}

// Crear hojas correderas con componentes específicos
createSlidingPanelsWithRealComponents(width, height, depth, frameWidth, materials, parentGroup) {
    const panelWidth = (width - frameWidth * 2) / 2; // Cada hoja ocupa la mitad del espacio interior
    const panelHeight = height - frameWidth * 2;
    const halfWidth = (width - frameWidth * 2) / 4; // Un cuarto del ancho interior desde el centro
    
    // HOJA 1 (IZQUIERDA) - Posición inicial: lado izquierdo dentro del marco
    const panel1 = this.createPanelWithRealProfiles(panelWidth, panelHeight, depth * 0.8, frameWidth * 0.8, materials, '1');
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
    const panel2 = this.createPanelWithRealProfiles(panelWidth, panelHeight, depth * 0.8, frameWidth * 0.8, materials, '2');
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
createPanelWithRealProfiles(width, height, depth, frameWidth, materials, panelNumber) {
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

// Sistema de vidriado mejorado con doble acristalamiento
createGlass(width, height, depth, frameWidth, material) {
    const glassGroup = new this.THREE.Group();
    const innerWidth = width - (frameWidth * 2);
    const innerHeight = height - (frameWidth * 2);
    
    const panelConfig = this.calculatePanelConfiguration(width, height);
    
    panelConfig.panels.forEach((panel, index) => {
        // Panel exterior
        const outerGlass = new this.THREE.Mesh(
            new this.THREE.BoxGeometry(panel.width, panel.height, 0.004), // 4mm de grosor
            material
        );
        outerGlass.name = `glass_outer_${index}`;
        outerGlass.position.set(panel.x, panel.y, depth * 0.15);
        glassGroup.add(outerGlass);
        
        // Panel interior
        const innerGlass = new this.THREE.Mesh(
            new this.THREE.BoxGeometry(panel.width, panel.height, 0.004),
            material
        );
        innerGlass.name = `glass_inner_${index}`;
        innerGlass.position.set(panel.x, panel.y, -depth * 0.15);
        glassGroup.add(innerGlass);
        
        // Separador de cámara de aire (espaciador aluminio)
        const spacerGeometry = new this.THREE.BoxGeometry(panel.width - 0.01, panel.height - 0.01, 0.002);
        const spacerMaterial = new this.THREE.MeshStandardMaterial({
            color: 0x666666,
            metalness: 0.8,
            roughness: 0.3
        });
        const spacer = new this.THREE.Mesh(spacerGeometry, spacerMaterial);
        spacer.position.set(panel.x, panel.y, 0);
        glassGroup.add(spacer);
        
        // Cámara de aire (gas argón - representación visual)
        const airGapGeometry = new this.THREE.BoxGeometry(panel.width - 0.02, panel.height - 0.02, depth * 0.25);
        const airGapMaterial = new this.THREE.MeshPhysicalMaterial({
            color: 0x88CCFF,
            transparent: true,
            opacity: 0.05,
            transmission: 0.98,
            roughness: 0.01
        });
        const airGap = new this.THREE.Mesh(airGapGeometry, airGapMaterial);
        airGap.position.set(panel.x, panel.y, 0);
        glassGroup.add(airGap);
    });
    
    return glassGroup;
}

// Herrajes mejorados con más detalle
createRealHardware(width, height, depth, materials) {
    const hardwareGroup = new this.THREE.Group();
    hardwareGroup.name = 'realHardware';
    
    // Sistema de ruedas mejorado
    const wheelsGroup = this.createAdvancedWheelSystem(width, height, depth);
    this.applyMaterialToProfile(wheelsGroup, materials.stainlessSteel);
    hardwareGroup.add(wheelsGroup);
    
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
    
    // Cuerpo principal del mecanismo
    const lockBodyGeometry = new this.THREE.BoxGeometry(0.06, 0.08, 0.025);
    const lockBody = new this.THREE.Mesh(lockBodyGeometry);
    lockBody.position.set(0, 0, depth / 2 + 0.015);
    lockGroup.add(lockBody);
    
    // Manija ergonómica
    const handleGeometry = new this.THREE.CylinderGeometry(0.006, 0.006, 0.06, 8);
    const handle = new this.THREE.Mesh(handleGeometry);
    handle.rotation.z = Math.PI / 2;
    handle.position.set(0, 0, depth / 2 + 0.035);
    lockGroup.add(handle);
    
    // Palanca de bloqueo
    const leverGeometry = new this.THREE.BoxGeometry(0.02, 0.004, 0.01);
    const lever = new this.THREE.Mesh(leverGeometry);
    lever.position.set(0.015, 0, depth / 2 + 0.025);
    lockGroup.add(lever);
    
    // Puntos de anclaje
    const anchorGeometry = new this.THREE.CylinderGeometry(0.004, 0.004, 0.01, 6);
    for (let i = -1; i <= 1; i += 2) {
        const anchor = new this.THREE.Mesh(anchorGeometry);
        anchor.position.set(i * 0.02, 0.03, depth / 2 + 0.01);
        lockGroup.add(anchor);
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

// Sistema de sellado mejorado
createSealing(width, height, depth, materials) {
    const sealingGroup = new this.THREE.Group();
    sealingGroup.name = 'advancedSealing';
    
    // Sellado perimetral de felpa
    const felpaGroup = this.createAdvancedFelpa(width, height, depth);
    this.applyMaterialToProfile(felpaGroup, materials.felt);
    sealingGroup.add(felpaGroup);
    
    // Sellado de juntas de goma
    const rubberGroup = this.createAdvancedRubberSeals(width, height, depth);
    this.applyMaterialToProfile(rubberGroup, materials.rubber);
    sealingGroup.add(rubberGroup);
    
    return sealingGroup;
}

// Sellado de felpa avanzado
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

// Sellados de goma avanzados
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
        // Crear closure para capturar variables del scope
        const windowWidth = width;
        const windowHeight = height;
        const windowDepth = depth;
        const windowFrameWidth = frameWidth;
        
        // Agregar funciones de animación de ruedas al userData
        group.userData.animateWheels = (panel, displacement) => {
            this.animateWheels(panel, displacement);
        };
        
        group.userData.resetWheels = (panel, panelNumber) => {
            this.resetWheels(panel, panelNumber);
        };
        
        group.userData.slidePanel = (panelNumber, action = 'toggle') => {
            
            // Acceso directo a paneles
            const panel = group.getObjectByName(`slidingPanel${panelNumber}`);
            const otherPanel = group.getObjectByName(`slidingPanel${panelNumber === '1' ? '2' : '1'}`);
            
            // Limpiar variables de control de animación anterior
            if (panel && panel.userData) {
                delete panel.userData.wheelLoggedStart;
                delete panel.userData.initialDisplacement;
            }
            
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
            const currentPanel1 = group.getObjectByName('slidingPanel1');
            const currentPanel2 = group.getObjectByName('slidingPanel2');
            
            if (currentPanel1 && currentPanel1.userData) {
                currentPanel1.position.x = currentPanel1.userData.originalX;
                currentPanel1.position.z = currentPanel1.userData.originalZ;
                currentPanel1.userData.isOpen = false;
                currentPanel1.userData.currentDisplacement = 0;
                // Resetear ruedas del panel 1
                this.resetWheels(currentPanel1, '1');
            }
            if (currentPanel2 && currentPanel2.userData) {
                currentPanel2.position.x = currentPanel2.userData.originalX;
                currentPanel2.position.z = currentPanel2.userData.originalZ;
                currentPanel2.userData.isOpen = false;
                currentPanel2.userData.currentDisplacement = 0;
                // Resetear ruedas del panel 2
                this.resetWheels(currentPanel2, '2');
            }
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

    // Animación de deslizamiento suave mejorada
    animateSliding(panel, targetX, duration) {
        if (!panel) return;
        
        const startX = panel.position.x;
        const distance = targetX - startX;
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
            
            panel.position.x = startX + distance * easeProgress;
            
            // Efecto de ruedas girando (opcional - rotar ruedas)
            this.animateWheels(panel, distance * easeProgress);
            
            if (progress < 1) {
                panel.userData.animationId = requestAnimationFrame(animate);
            } else {
                // Animación completada
                delete panel.userData.animationId;
                // Asegurar posición final exacta
                panel.position.x = targetX;
            }
        };
        
        animate();
    }
    
    // Mover ruedas horizontalmente con el panel (mecánica real de ventana corredera)
    animateWheels(panel, displacement) {
        // Buscar ruedas tanto en el panel como en el grupo principal
        const windowGroup = panel.parent;
        
        // Identificar qué panel es (1 o 2) para mover sus ruedas específicas
        const panelNumber = panel.name === 'slidingPanel1' ? '1' : '2';
        
        if (windowGroup) {
            let wheelsMoved = 0;
            windowGroup.traverse((child) => {
                if (child.name && child.name.includes('rueda')) {
                    // Determinar si esta rueda pertenece al panel que se está moviendo
                    const wheelBelongsToPanel = this.wheelBelongsToPanel(child, panelNumber, windowGroup);
                    
                    if (wheelBelongsToPanel) {
                        // Guardar posición inicial si no existe
                        if (child.userData.initialPositionX === undefined) {
                            child.userData.initialPositionX = child.position.x;
                            child.userData.initialPositionZ = child.position.z;
                        }
                        
                        // DESPLAZAR las ruedas horizontalmente con el panel
                        // En una ventana corredera real, las ruedas se mueven con la hoja
                        const panelMovementX = panel.position.x - panel.userData.originalX;
                        const panelMovementZ = panel.position.z - panel.userData.originalZ;
                        
                        // Las ruedas siguen exactamente el movimiento del panel
                        child.position.x = child.userData.initialPositionX + panelMovementX;
                        child.position.z = child.userData.initialPositionZ + panelMovementZ;
                        
                        // NO hay rotación - las ruedas solo se desplazan
                        // Mantener altura original (ruedas en la parte superior)
                        
                        wheelsMoved++;
                    }
                }
            });
            
            // Log solo al inicio de la animación
            if (!panel.userData.wheelLoggedStart && wheelsMoved > 0) {
                panel.userData.wheelLoggedStart = true;
            }
        }
    }
    
    // Determinar si una rueda pertenece al panel que se está moviendo
    wheelBelongsToPanel(wheel, panelNumber, windowGroup) {
        // MÉTODO SIMPLIFICADO: Solo animar ruedas por posición espacial
        // Esto evita problemas con nombres de ruedas inconsistentes
        
        const wheelWorldPos = new this.THREE.Vector3();
        wheel.getWorldPosition(wheelWorldPos);
        
        const panel = windowGroup.getObjectByName(`slidingPanel${panelNumber}`);
        if (!panel) return false;
        
        const panelWorldPos = new this.THREE.Vector3();
        panel.getWorldPosition(panelWorldPos);
        
        // Usar distancia en X para determinar qué ruedas pertenecen al panel
        const distanceX = Math.abs(wheelWorldPos.x - panelWorldPos.x);
        
        // Solo considerar ruedas muy cercanas al panel (umbral muy estricto)
        const belongs = distanceX < 0.15;
        
        return belongs;
    }
    
    // Resetear ruedas a posición original
    resetWheels(panel, panelNumber) {
        const windowGroup = panel.parent;
        
        if (windowGroup) {
            let wheelsReset = 0;
            windowGroup.traverse((child) => {
                if (child.name && child.name.includes('rueda')) {
                    const wheelBelongsToPanel = this.wheelBelongsToPanel(child, panelNumber, windowGroup);
                    
                    if (wheelBelongsToPanel) {
                        // Resetear posición horizontal a posición inicial
                        if (child.userData.initialPositionX !== undefined) {
                            child.position.x = child.userData.initialPositionX;
                            delete child.userData.initialPositionX;
                        }
                        
                        // Resetear posición en Z a posición inicial
                        if (child.userData.initialPositionZ !== undefined) {
                            child.position.z = child.userData.initialPositionZ;
                            delete child.userData.initialPositionZ;
                        }
                        
                        wheelsReset++;
                    }
                }
            });
            
            // Limpiar variables de control del panel
            delete panel.userData.wheelLoggedStart;
        }
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
            
            // Efecto de ruedas girando - usar desplazamiento total desde el inicio
            const totalDisplacement = distanceX * easeProgress;
            this.animateWheels(panel, totalDisplacement);
            
            // Almacenar desplazamiento actual para la siguiente frame
            panel.userData.currentDisplacement = totalDisplacement;
            
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
        
        // Información de controles
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

    // Tipos de ventana disponibles
    static getWindowTypes() {
        return [
            { 
                id: 'fixed', 
                name: 'Ventana Fija', 
                description: 'Paño fijo sin herrajes de apertura' 
            },
            { 
                id: 'casement', 
                name: 'Ventana Abatible', 
                description: 'Apertura lateral con bisagras' 
            },
            { 
                id: 'sliding', 
                name: 'Corredera', 
                description: 'Apertura deslizante horizontal' 
            },
            { 
                id: 'awning', 
                name: 'Proyectante', 
                description: 'Apertura hacia exterior tipo toldo' 
            }
        ];
    }
}

// Exposición global para compatibilidad con scripts
if (typeof window !== 'undefined') {
    window.WindowModel = WindowModel;
}
