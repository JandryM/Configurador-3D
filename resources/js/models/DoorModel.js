// Modelo 3D específico para puertas
export class DoorModel {
    constructor(THREE) {
        this.THREE = THREE;
    }

    // Generar puerta completa
    generate(parameters) {
        console.log('🚪 Iniciando generación de puerta...');
        const group = new this.THREE.Group();
        const { width, height, depth, frameWidth } = parameters;
        console.log('📏 Dimensiones puerta:', { width, height, depth, frameWidth });

        // Materiales dinámicos
        const materials = this.createMaterials(parameters);
        
        // Componentes principales
        const frameGroup = this.createFrame(width, height, depth, frameWidth, materials.frame);
        const doorGroup = this.createDoor(width, height, depth, frameWidth, materials.door);
        const hardwareGroup = this.createHardware(width, height, depth, materials.metal);

        // Ensamblar
        group.add(frameGroup);
        group.add(doorGroup);
        group.add(hardwareGroup);
        
        console.log('🚪 Elementos agregados al grupo:', group.children.length);

        // Configurar sombras
        this.setupShadows(group);

        console.log('✅ Puerta generada con', group.children.length, 'elementos');
        return group;
    }

    // Crear materiales
    createMaterials(parameters) {
        const frameColor = parameters.frameColor || '#2F4F4F';
        const doorColor = parameters.color || '#8B4513';
        
        return {
            frame: new this.THREE.MeshPhongMaterial({ 
                color: new this.THREE.Color(frameColor),
                shininess: 20
            }),
            door: new this.THREE.MeshPhongMaterial({ 
                color: new this.THREE.Color(doorColor),
                shininess: 30
            }),
            metal: new this.THREE.MeshPhongMaterial({ 
                color: 0x666666,
                shininess: 80
            })
        };
    }

    // Crear marco de puerta
    createFrame(width, height, depth, frameWidth, material) {
        const frameGroup = new this.THREE.Group();
        
        // Marco superior
        const topFrame = new this.THREE.Mesh(
            new this.THREE.BoxGeometry(width + frameWidth * 2, frameWidth, depth),
            material
        );
        topFrame.position.y = height / 2 + frameWidth / 2;
        frameGroup.add(topFrame);
        
        // Marco izquierdo
        const leftFrame = new this.THREE.Mesh(
            new this.THREE.BoxGeometry(frameWidth, height, depth),
            material
        );
        leftFrame.position.x = -(width / 2 + frameWidth / 2);
        frameGroup.add(leftFrame);
        
        // Marco derecho
        const rightFrame = new this.THREE.Mesh(
            new this.THREE.BoxGeometry(frameWidth, height, depth),
            material
        );
        rightFrame.position.x = width / 2 + frameWidth / 2;
        frameGroup.add(rightFrame);
        
        return frameGroup;
    }

    // Crear hoja de puerta
    createDoor(width, height, depth, frameWidth, material) {
        const doorGroup = new this.THREE.Group();
        
        // Hoja principal
        const doorGeometry = new this.THREE.BoxGeometry(width * 0.95, height * 0.95, depth * 0.8);
        const door = new this.THREE.Mesh(doorGeometry, material);
        door.position.z = depth * 0.1;
        doorGroup.add(door);
        
        // Paneles decorativos (opcionales)
        if (height > 1.8) {
            this.addDoorPanels(doorGroup, width, height, depth, material);
        }
        
        return doorGroup;
    }

    // Agregar paneles decorativos
    addDoorPanels(group, width, height, depth, material) {
        const panelDepth = 0.02;
        const panelWidth = width * 0.7;
        const panelHeight = height * 0.15;
        
        // Panel superior
        const upperPanel = new this.THREE.Mesh(
            new this.THREE.BoxGeometry(panelWidth, panelHeight, panelDepth),
            material
        );
        upperPanel.position.set(0, height * 0.25, depth * 0.5);
        group.add(upperPanel);
        
        // Panel inferior
        const lowerPanel = new this.THREE.Mesh(
            new this.THREE.BoxGeometry(panelWidth, panelHeight, panelDepth),
            material
        );
        lowerPanel.position.set(0, -height * 0.25, depth * 0.5);
        group.add(lowerPanel);
    }

    // Crear herraje (manija, bisagras)
    createHardware(width, height, depth, material) {
        const hardwareGroup = new this.THREE.Group();
        
        // Manija
        const handleGeometry = new this.THREE.CylinderGeometry(0.02, 0.02, 0.1);
        const handle = new this.THREE.Mesh(handleGeometry, material);
        handle.position.set(width * 0.35, 0, depth * 0.6);
        handle.rotation.z = Math.PI / 2;
        hardwareGroup.add(handle);
        
        // Cerradura
        const lockGeometry = new this.THREE.BoxGeometry(0.06, 0.1, 0.03);
        const lock = new this.THREE.Mesh(lockGeometry, material);
        lock.position.set(width * 0.35, -0.1, depth * 0.5);
        hardwareGroup.add(lock);
        
        // Bisagras
        this.addHinges(hardwareGroup, width, height, depth, material);
        
        return hardwareGroup;
    }

    // Agregar bisagras
    addHinges(group, width, height, depth, material) {
        const hingeCount = 3;
        
        for (let i = 0; i < hingeCount; i++) {
            const hinge = new this.THREE.Mesh(
                new this.THREE.BoxGeometry(0.02, 0.08, 0.03),
                material
            );
            
            const yOffset = (height/2) - (i * height/(hingeCount-1)) - (height/4);
            hinge.position.set(-width/2, yOffset, depth/2 + 0.01);
            group.add(hinge);
        }
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

    // Tipos de puerta disponibles
    static getDoorTypes() {
        return [
            { id: 'standard', name: 'Puerta Estándar', description: 'Puerta básica de una hoja' },
            { id: 'double', name: 'Puerta Doble', description: 'Puerta de dos hojas' },
            { id: 'sliding', name: 'Puerta Corrediza', description: 'Puerta deslizante' },
            { id: 'folding', name: 'Puerta Plegable', description: 'Puerta tipo acordeón' }
        ];
    }
}