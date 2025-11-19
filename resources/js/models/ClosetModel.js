// Modelo 3D mejorado para closet de melamina con texturas y funcionalidad interactiva
export class ClosetModel {
    constructor(THREE) {
        this.THREE = THREE;
        this.textureCache = new Map();
        this.materialCache = new Map();
    }

    generate(parameters) {
        const group = new this.THREE.Group();
        const { width = 1.5, height = 2.0, depth = 0.6 } = parameters;

        // Materiales con texturas de melamina
        const materials = this.createMelamineMaterials(parameters);
        
        // Estructura principal del closet
        const structure = this.createClosetStructure(width, height, depth, materials);
        group.add(structure);

        // Puertas corredizas con funcionalidad
        const doors = this.createSlidingDoors(width, height, depth, materials);
        group.add(doors);

        // Cajones internos con funcionalidad
        const drawers = this.createDrawers(width, height, depth, materials);
        group.add(drawers);

        // Estantes ajustables
        const shelves = this.createShelves(width, height, depth, materials);
        group.add(shelves);

        // Herrajes y manijas
        const hardware = this.createHardware(width, height, depth, materials);
        group.add(hardware);

        // Configurar sombras
        this.setupShadows(group);

        // Funcionalidad interactiva
        this.setupInteractivity(group, width, height, depth);

        // Función de actualización de colores
        group.userData.updateColors = (newParameters) => {
            this.clearMaterialCache();
            const newMaterials = this.createMelamineMaterials(newParameters);
            this.updateGroupMaterials(group, newMaterials);
        };

        return group;
    }

    // Crear materiales de melamina con texturas
    createMelamineMaterials(parameters) {
        const texturePath = parameters.texturePath || '/textures/melamina/natural/';
        const colorKey = texturePath;

        if (this.materialCache.has(colorKey)) {
            return this.materialCache.get(colorKey);
        }

        const textureLoader = new this.THREE.TextureLoader();
        
        // Cargar texturas de melamina
        let baseColor, roughness, normal;
        try {
            baseColor = textureLoader.load(`${texturePath}basecolor.jpg`);
            roughness = textureLoader.load(`${texturePath}roughness.jpg`);
            normal = textureLoader.load(`${texturePath}normal.jpg`);

            // Configurar texturas
            [baseColor, roughness, normal].forEach(texture => {
                texture.wrapS = texture.wrapT = this.THREE.RepeatWrapping;
                texture.repeat.set(2, 2);
            });
        } catch (error) {
            console.warn('Error cargando texturas de melamina, usando color sólido');
        }

        const materials = {
            melamina: new this.THREE.MeshPhysicalMaterial({
                map: baseColor || null,
                roughnessMap: roughness || null,
                normalMap: normal || null,
                color: baseColor ? 0xffffff : 0xD2B48C,
                roughness: 0.6,
                metalness: 0.0,
                clearcoat: 0.05,
                clearcoatRoughness: 0.3,
            }),
            handle: new this.THREE.MeshPhysicalMaterial({
                color: 0x2c2c2c,
                metalness: 0.9,
                roughness: 0.2,
                clearcoat: 0.5,
                clearcoatRoughness: 0.1
            }),
            metal: new this.THREE.MeshPhysicalMaterial({
                color: 0xC0C0C0,
                metalness: 0.95,
                roughness: 0.15,
                clearcoat: 0.3
            })
        };

        this.materialCache.set(colorKey, materials);
        return materials;
    }

    // Crear estructura principal (carcasa)
    createClosetStructure(width, height, depth, materials) {
        const structureGroup = new this.THREE.Group();
        structureGroup.name = 'closetStructure';

        const thickness = 0.018; // Grosor de la melamina (18mm estándar)

        // Laterales
        const sideGeometry = new this.THREE.BoxGeometry(thickness, height, depth);
        const leftSide = new this.THREE.Mesh(sideGeometry, materials.melamina);
        leftSide.position.x = -(width - thickness) / 2;
        leftSide.name = 'leftSide';
        structureGroup.add(leftSide);

        const rightSide = new this.THREE.Mesh(sideGeometry, materials.melamina);
        rightSide.position.x = (width - thickness) / 2;
        rightSide.name = 'rightSide';
        structureGroup.add(rightSide);

        // Tapa superior
        const topGeometry = new this.THREE.BoxGeometry(width, thickness, depth);
        const top = new this.THREE.Mesh(topGeometry, materials.melamina);
        top.position.y = (height - thickness) / 2;
        top.name = 'top';
        structureGroup.add(top);

        // Base
        const bottom = new this.THREE.Mesh(topGeometry, materials.melamina);
        bottom.position.y = -(height - thickness) / 2;
        bottom.name = 'bottom';
        structureGroup.add(bottom);

        // Fondo
        const backGeometry = new this.THREE.BoxGeometry(width - thickness * 2, height - thickness * 2, thickness / 2);
        const back = new this.THREE.Mesh(backGeometry, materials.melamina);
        back.position.z = -(depth - thickness / 2) / 2;
        back.name = 'back';
        structureGroup.add(back);

        return structureGroup;
    }

    // Crear puertas corredizas
    createSlidingDoors(width, height, depth, materials) {
        const doorsGroup = new this.THREE.Group();
        doorsGroup.name = 'slidingDoors';

        const doorWidth = width / 2 - 0.01;
        const doorHeight = height - 0.06;
        const doorDepth = 0.018;

        // Puerta izquierda
        const leftDoor = this.createDoor(doorWidth, doorHeight, doorDepth, materials, 'left');
        leftDoor.position.x = -doorWidth / 2 - 0.005;
        leftDoor.position.z = depth / 2 + doorDepth / 2;
        leftDoor.userData.originalX = leftDoor.position.x;
        leftDoor.userData.originalZ = leftDoor.position.z;
        leftDoor.userData.isOpen = false;
        doorsGroup.add(leftDoor);

        // Puerta derecha
        const rightDoor = this.createDoor(doorWidth, doorHeight, doorDepth, materials, 'right');
        rightDoor.position.x = doorWidth / 2 + 0.005;
        rightDoor.position.z = depth / 2 + doorDepth / 2 - 0.01;
        rightDoor.userData.originalX = rightDoor.position.x;
        rightDoor.userData.originalZ = rightDoor.position.z;
        rightDoor.userData.isOpen = false;
        doorsGroup.add(rightDoor);

        return doorsGroup;
    }

    // Crear una puerta individual
    createDoor(width, height, depth, materials, side) {
        const doorGroup = new this.THREE.Group();
        doorGroup.name = `door${side.charAt(0).toUpperCase() + side.slice(1)}`;

        // Panel de la puerta
        const doorGeometry = new this.THREE.BoxGeometry(width, height, depth);
        const doorMesh = new this.THREE.Mesh(doorGeometry, materials.melamina);
        doorGroup.add(doorMesh);

        // Manija vertical
        const handleHeight = 0.25;
        const handleRadius = 0.012;
        const handleGeometry = new this.THREE.CylinderGeometry(handleRadius, handleRadius, handleHeight, 16);
        const handle = new this.THREE.Mesh(handleGeometry, materials.handle);
        handle.position.x = side === 'left' ? width * 0.4 : -width * 0.4;
        handle.position.z = depth / 2 + 0.015;
        doorGroup.add(handle);

        return doorGroup;
    }

    // Crear cajones internos
    createDrawers(width, height, depth, materials) {
        const drawersGroup = new this.THREE.Group();
        drawersGroup.name = 'drawers';

        const drawerCount = 3;
        const drawerHeight = (height / 2) / (drawerCount + 1);
        const drawerWidth = width * 0.45;
        const drawerDepth = depth * 0.85;

        for (let i = 0; i < drawerCount; i++) {
            const drawer = this.createDrawer(drawerWidth, drawerHeight, drawerDepth, materials);
            drawer.position.x = width * 0.25;
            drawer.position.y = -height / 4 + (i - 1) * drawerHeight;
            drawer.position.z = -depth * 0.05;
            drawer.userData.originalZ = drawer.position.z;
            drawer.userData.isOpen = false;
            drawer.userData.index = i;
            drawer.name = `drawer${i}`;
            drawersGroup.add(drawer);
        }

        return drawersGroup;
    }

    // Crear un cajón individual
    createDrawer(width, height, depth, materials) {
        const drawerGroup = new this.THREE.Group();
        const thickness = 0.015;

        // Frente del cajón
        const frontGeometry = new this.THREE.BoxGeometry(width, height - 0.01, thickness);
        const front = new this.THREE.Mesh(frontGeometry, materials.melamina);
        front.position.z = depth / 2;
        drawerGroup.add(front);

        // Lados del cajón
        const sideGeometry = new this.THREE.BoxGeometry(thickness, height - 0.02, depth - thickness);
        const leftSide = new this.THREE.Mesh(sideGeometry, materials.melamina);
        leftSide.position.x = -width / 2 + thickness / 2;
        leftSide.position.z = -thickness / 2;
        drawerGroup.add(leftSide);

        const rightSide = leftSide.clone();
        rightSide.position.x = width / 2 - thickness / 2;
        drawerGroup.add(rightSide);

        // Fondo del cajón
        const bottomGeometry = new this.THREE.BoxGeometry(width - thickness * 2, thickness / 2, depth - thickness);
        const bottom = new this.THREE.Mesh(bottomGeometry, materials.melamina);
        bottom.position.y = -(height - 0.02) / 2;
        bottom.position.z = -thickness / 2;
        drawerGroup.add(bottom);

        // Manija del cajón
        const handleGeometry = new this.THREE.CylinderGeometry(0.008, 0.008, 0.08, 16);
        const handle = new this.THREE.Mesh(handleGeometry, materials.handle);
        handle.rotation.z = Math.PI / 2;
        handle.position.z = depth / 2 + 0.01;
        drawerGroup.add(handle);

        return drawerGroup;
    }

    // Crear estantes ajustables
    createShelves(width, height, depth, materials) {
        const shelvesGroup = new this.THREE.Group();
        shelvesGroup.name = 'shelves';

        const shelfCount = 4;
        const shelfThickness = 0.018;
        const shelfWidth = width * 0.45;

        for (let i = 1; i <= shelfCount; i++) {
            const shelfGeometry = new this.THREE.BoxGeometry(shelfWidth, shelfThickness, depth - 0.04);
            const shelf = new this.THREE.Mesh(shelfGeometry, materials.melamina);
            shelf.position.x = -width * 0.25;
            shelf.position.y = -height / 2 + (i * height) / (shelfCount + 1);
            shelf.name = `shelf${i}`;
            shelvesGroup.add(shelf);
        }

        return shelvesGroup;
    }

    // Crear herrajes y manijas adicionales
    createHardware(width, height, depth, materials) {
        const hardwareGroup = new this.THREE.Group();
        hardwareGroup.name = 'hardware';

        // Rieles superiores e inferiores para puertas corredizas
        const railGeometry = new this.THREE.BoxGeometry(width, 0.015, 0.03);
        const topRail = new this.THREE.Mesh(railGeometry, materials.metal);
        topRail.position.y = height / 2 - 0.03;
        topRail.position.z = depth / 2;
        hardwareGroup.add(topRail);

        const bottomRail = topRail.clone();
        bottomRail.position.y = -height / 2 + 0.03;
        hardwareGroup.add(bottomRail);

        return hardwareGroup;
    }

    // Configurar interactividad (abrir/cerrar puertas y cajones)
    setupInteractivity(group, width, height, depth) {
        group.userData.toggleDoor = (side) => {
            const doorName = `door${side.charAt(0).toUpperCase() + side.slice(1)}`;
            const door = group.getObjectByName(doorName);
            
            if (door) {
                const slideDistance = width * 0.4;
                const targetX = door.userData.isOpen 
                    ? door.userData.originalX 
                    : door.userData.originalX + (side === 'left' ? slideDistance : -slideDistance);
                
                this.animateSlide(door, targetX, door.position.z, 500);
                door.userData.isOpen = !door.userData.isOpen;
            }
        };

        group.userData.toggleDrawer = (index) => {
            const drawer = group.getObjectByName(`drawer${index}`);
            
            if (drawer) {
                const slideDistance = depth * 0.5;
                const targetZ = drawer.userData.isOpen 
                    ? drawer.userData.originalZ 
                    : drawer.userData.originalZ + slideDistance;
                
                this.animateSlide(drawer, drawer.position.x, targetZ, 400);
                drawer.userData.isOpen = !drawer.userData.isOpen;
            }
        };

        // Manejar clicks
        group.userData.handleClick = (intersectedObject) => {
            let current = intersectedObject;
            while (current && current !== group) {
                if (current.name && current.name.startsWith('door')) {
                    const side = current.name.includes('Left') ? 'left' : 'right';
                    group.userData.toggleDoor(side);
                    return true;
                } else if (current.name && current.name.startsWith('drawer')) {
                    const index = parseInt(current.name.replace('drawer', ''));
                    group.userData.toggleDrawer(index);
                    return true;
                }
                current = current.parent;
            }
            return false;
        };
    }

    // Animación de deslizamiento
    animateSlide(object, targetX, targetZ, duration) {
        const startX = object.position.x;
        const startZ = object.position.z;
        const startTime = Date.now();

        const animate = () => {
            const elapsed = Date.now() - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const eased = this.easeInOutCubic(progress);

            object.position.x = startX + (targetX - startX) * eased;
            object.position.z = startZ + (targetZ - startZ) * eased;

            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };

        animate();
    }

    // Función de suavizado
    easeInOutCubic(t) {
        return t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
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

    // Actualizar materiales del grupo
    updateGroupMaterials(group, materials) {
        group.traverse((child) => {
            if (child.isMesh) {
                if (child.parent.name.includes('door') || 
                    child.parent.name.includes('drawer') || 
                    child.parent.name.includes('shelf') ||
                    child.parent.name.includes('closetStructure')) {
                    child.material = materials.melamina;
                } else if (child.material && child.material.metalness > 0.5) {
                    // Mantener herrajes metálicos
                    if (child.material.color.getHex() === 0x2c2c2c) {
                        child.material = materials.handle;
                    } else {
                        child.material = materials.metal;
                    }
                }
            }
        });
    }

    // Limpiar cache de materiales
    clearMaterialCache() {
        this.materialCache.forEach(materials => {
            Object.values(materials).forEach(material => {
                if (material.map) material.map.dispose();
                if (material.roughnessMap) material.roughnessMap.dispose();
                if (material.normalMap) material.normalMap.dispose();
                material.dispose();
            });
        });
        this.materialCache.clear();
    }

    // Limpiar todo
    destroy() {
        this.clearMaterialCache();
        this.textureCache.clear();
    }
}

// Exposición global para compatibilidad
if (typeof window !== 'undefined') {
    window.ClosetModel = ClosetModel;
}
