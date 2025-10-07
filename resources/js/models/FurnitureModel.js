// Modelo 3D específico para muebles
export class FurnitureModel {
    constructor(THREE) {
        this.THREE = THREE;
    }

    // Generar mueble completo
    generate(parameters) {
        console.log('🪑 Iniciando generación de mueble...');
        const group = new this.THREE.Group();
        const { width, height, depth } = parameters;
        console.log('📏 Dimensiones mueble:', { width, height, depth });

        // Materiales dinámicos
        const materials = this.createMaterials(parameters);
        
        // Componentes principales
        const bodyGroup = this.createBody(width, height, depth, materials.main);
        const baseGroup = this.createBase(width, height, depth, materials.main);
        
        // Agregar patas si es necesario
        if (parameters.legs) {
            const legsGroup = this.createLegs(width, height, depth, materials.accent);
            group.add(legsGroup);
        }

        // Ensamblar
        group.add(bodyGroup);
        group.add(baseGroup);
        
        console.log('🪑 Elementos agregados al grupo:', group.children.length);

        // Configurar sombras
        this.setupShadows(group);

        console.log('✅ Mueble generado con', group.children.length, 'elementos');
        return group;
    }

    // Crear materiales
    createMaterials(parameters) {
        const mainColor = parameters.color || '#8B4513';
        const accentColor = parameters.accentColor || '#4a4a4a';
        
        return {
            main: new this.THREE.MeshPhongMaterial({ 
                color: new this.THREE.Color(mainColor),
                shininess: 25
            }),
            accent: new this.THREE.MeshPhongMaterial({ 
                color: new this.THREE.Color(accentColor),
                shininess: 15
            })
        };
    }

    // Crear cuerpo principal
    createBody(width, height, depth, material) {
        const bodyGroup = new this.THREE.Group();
        
        // Cuerpo principal
        const bodyGeometry = new this.THREE.BoxGeometry(width, height * 0.8, depth);
        const body = new this.THREE.Mesh(bodyGeometry, material);
        body.position.y = height * 0.4;
        bodyGroup.add(body);
        
        return bodyGroup;
    }

    // Crear base
    createBase(width, height, depth, material) {
        const baseGroup = new this.THREE.Group();
        
        // Base
        const baseGeometry = new this.THREE.BoxGeometry(width * 1.1, height * 0.1, depth * 1.1);
        const base = new this.THREE.Mesh(baseGeometry, material);
        base.position.y = height * 0.05;
        baseGroup.add(base);
        
        return baseGroup;
    }

    // Crear patas
    createLegs(width, height, depth, material) {
        const legsGroup = new this.THREE.Group();
        
        const legGeometry = new this.THREE.CylinderGeometry(0.02, 0.02, height * 0.3);
        
        const positions = [
            [-width * 0.4, height * 0.15, -depth * 0.4],
            [width * 0.4, height * 0.15, -depth * 0.4],
            [-width * 0.4, height * 0.15, depth * 0.4],
            [width * 0.4, height * 0.15, depth * 0.4]
        ];

        positions.forEach(pos => {
            const leg = new this.THREE.Mesh(legGeometry, material);
            leg.position.set(...pos);
            legsGroup.add(leg);
        });
        
        return legsGroup;
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

    // Tipos de mueble disponibles
    static getFurnitureTypes() {
        return [
            { id: 'table', name: 'Mesa', description: 'Mesa básica con patas' },
            { id: 'chair', name: 'Silla', description: 'Silla estándar' },
            { id: 'cabinet', name: 'Gabinete', description: 'Gabinete de almacenamiento' },
            { id: 'shelf', name: 'Estantería', description: 'Estantería modular' }
        ];
    }
}