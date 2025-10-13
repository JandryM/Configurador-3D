// Three.js se importa globalmente desde app.js
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';
import { RGBELoader } from 'three/examples/jsm/loaders/RGBELoader.js';
import { EXRLoader } from 'three/examples/jsm/loaders/EXRLoader.js';
import { WindowModel } from './models/WindowModel.js';
import { DoorModel } from './models/DoorModel.js';
import { FurnitureModel } from './models/FurnitureModel.js';

class ParametricProduct3D {
    constructor(containerId, productType, initialParams = {}) {
        // Verificar que THREE esté disponible
        if (typeof window.THREE === 'undefined') {
            throw new Error('Three.js no está disponible en window.THREE');
        }
        
        this.THREE = window.THREE;
        this.container = document.getElementById(containerId);
        
        if (!this.container) {
            throw new Error(`Contenedor con ID '${containerId}' no encontrado`);
        }
        
        this.productType = productType;
        this.parameters = {
            width: 1.0,
            height: 1.0,
            depth: 0.1,
            frameWidth: 0.05,
            color: '#8B4513',
            glassColor: '#87CEEB',
            frameColor: '#2F4F4F',
            ...initialParams
        };

        this.scene = null;
        this.camera = null;
        this.renderer = null;
        this.controls = null;
        this.productMesh = null;
        this.animationId = null;
        this.cameraInitialized = false;

        // Configurar loaders HDRI para WindowModel
        this.hdriLoaders = {
            RGBELoader: RGBELoader,
            EXRLoader: EXRLoader
        };
        
        console.log('✅ Loaders HDRI configurados (RGBELoader y EXRLoader)');

        // Inicializar modelos especializados
        this.models = {
            window: new WindowModel(this.THREE),
            door: new DoorModel(this.THREE),
            furniture: new FurnitureModel(this.THREE)
        };

        this.init();
        this.generateProduct();
    }

    init() {
        const THREE = this.THREE;
        
        // Configurar escena básica
        this.scene = new THREE.Scene();
        this.scene.background = new THREE.Color(0xf5f5f5);

        // Cámara
        this.camera = new THREE.PerspectiveCamera(
            75,
            this.container.clientWidth / this.container.clientHeight,
            0.1,
            1000
        );
        this.camera.position.set(3, 3, 3);

        // Renderer con configuración avanzada
        this.renderer = new THREE.WebGLRenderer({ 
            antialias: true,
            alpha: true
        });
        this.renderer.setSize(this.container.clientWidth, this.container.clientHeight);
        this.renderer.setPixelRatio(window.devicePixelRatio);
        
        // Configuración avanzada de sombras
        this.renderer.shadowMap.enabled = true;
        this.renderer.shadowMap.type = THREE.PCFSoftShadowMap;
        
        // Configuración de renderizado optimizada para colores
        this.renderer.gammaOutput = true;
        this.renderer.gammaFactor = 2.2;
        this.renderer.toneMapping = THREE.ACESFilmicToneMapping;
        this.renderer.toneMappingExposure = 1.0;
        
        // Fondo gris neutro para mejor contraste de colores
        this.renderer.setClearColor(0xf5f5f5);
        
        // Asegurar que el canvas sea visible
        this.renderer.domElement.style.position = 'absolute';
        this.renderer.domElement.style.top = '0';
        this.renderer.domElement.style.left = '0';
        this.renderer.domElement.style.zIndex = '10';
        this.renderer.domElement.style.pointerEvents = 'auto';
        
        this.container.appendChild(this.renderer.domElement);

        // Controles
        this.controls = new OrbitControls(this.camera, this.renderer.domElement);
        this.controls.enableDamping = true;

        // Configurar interacción para ventanas deslizables
        this.setupInteraction();
        
        // Configurar controles de teclado
        this.setupKeyboardControls();

        // Iluminación
        this.setupLighting();

        // Iniciar animación
        this.animate();
    }

    // Efecto visual para panel específico
    flashPanel(panel) {
        if (!panel) return;
        
        // Guardar material original
        const originalMaterials = [];
        panel.traverse((child) => {
            if (child.isMesh && child.material) {
                originalMaterials.push({
                    mesh: child,
                    material: child.material
                });
            }
        });
        
        // Material de destaque temporal
        const flashMaterial = new this.THREE.MeshBasicMaterial({
            color: 0x00ff00,
            transparent: true,
            opacity: 0.5
        });
        
        // Aplicar efecto de flash
        panel.traverse((child) => {
            if (child.isMesh && child.material) {
                child.material = flashMaterial;
            }
        });
        
        // Restaurar materiales originales después de 200ms
        setTimeout(() => {
            originalMaterials.forEach(({ mesh, material }) => {
                mesh.material = material;
            });
        }, 200);
    }
    
    // Efecto visual para ventana completa
    flashWindow(windowGroup) {
        if (!windowGroup) return;
        
        // Flash más sutil para toda la ventana
        const panel1 = windowGroup.getObjectByName('slidingPanel1');
        const panel2 = windowGroup.getObjectByName('slidingPanel2');
        
        if (panel1) this.flashPanel(panel1);
        if (panel2) {
            setTimeout(() => {
                this.flashPanel(panel2);
            }, 100); // Efecto escalonado
        }
    }
    
    // Agregar controles de teclado
    setupKeyboardControls() {
        document.addEventListener('keydown', (event) => {
            if (this.productMesh && this.productMesh.userData && this.productMesh.userData.handleKeyPress) {
                this.productMesh.userData.handleKeyPress(event);
            }
        });
    }

    setupLighting() {
        // Luz ambiental más brillante para mejor visibilidad de colores
        const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
        this.scene.add(ambientLight);

        // Luz direccional principal optimizada para colores
        const mainLight = new THREE.DirectionalLight(0xffffff, 1.0);
        mainLight.position.set(10, 15, 10);
        mainLight.castShadow = true;
        
        // Configuración avanzada de sombras
        mainLight.shadow.mapSize.width = 4096;
        mainLight.shadow.mapSize.height = 4096;
        mainLight.shadow.camera.near = 0.1;
        mainLight.shadow.camera.far = 50;
        mainLight.shadow.camera.left = -15;
        mainLight.shadow.camera.right = 15;
        mainLight.shadow.camera.top = 15;
        mainLight.shadow.camera.bottom = -15;
        mainLight.shadow.bias = -0.0001;
        
        this.scene.add(mainLight);

        // Luces de relleno más intensas para mejor contraste de colores
        const fillLight = new THREE.DirectionalLight(0xffffff, 0.5);
        fillLight.position.set(-8, 8, -5);
        this.scene.add(fillLight);

        // Luz trasera para dar profundidad y mejor definición
        const backLight = new THREE.DirectionalLight(0xffffff, 0.4);
        backLight.position.set(3, 5, -8);
        this.scene.add(backLight);
        
        // Luz adicional desde abajo para eliminar sombras duras
        const bottomLight = new THREE.DirectionalLight(0xffffff, 0.3);
        bottomLight.position.set(0, -5, 5);
        this.scene.add(bottomLight);
    }

    setupInteraction() {
        // Configurar Raycaster para detección de clics
        this.raycaster = new this.THREE.Raycaster();
        this.mouse = new this.THREE.Vector2();
        
        // Event listener para clics
        this.renderer.domElement.addEventListener('click', (event) => {
            this.onWindowClick(event);
        });
        
        // Event listener para hover (opcional)
        this.renderer.domElement.addEventListener('mousemove', (event) => {
            this.onWindowHover(event);
        });
    }

    onWindowClick(event) {
        // Prevenir que el OrbitControls interfiera
        event.preventDefault();
        event.stopPropagation();
        
        // Calcular coordenadas del mouse
        const rect = this.renderer.domElement.getBoundingClientRect();
        this.mouse.x = ((event.clientX - rect.left) / rect.width) * 2 - 1;
        this.mouse.y = -((event.clientY - rect.top) / rect.height) * 2 + 1;
        
        // Raycast para detecter objetos
        this.raycaster.setFromCamera(this.mouse, this.camera);
        
        if (this.productMesh) {
            const intersects = this.raycaster.intersectObjects(this.productMesh.children, true);
            
            if (intersects.length > 0) {
                let clickedObject = intersects[0].object;
                let windowGroup = null;
                let specificPanel = null;
                
                // Buscar ventana deslizable y panel específico
                while (clickedObject.parent && !windowGroup) {
                    // Verificar si es un panel específico
                    if (clickedObject.name && (clickedObject.name === 'slidingPanel1' || clickedObject.name === 'slidingPanel2')) {
                        specificPanel = clickedObject.name;
                    }
                    
                    // Buscar el grupo principal de la ventana
                    if (clickedObject.parent.userData && clickedObject.parent.userData.slideWindow) {
                        windowGroup = clickedObject.parent;
                        break;
                    }
                    clickedObject = clickedObject.parent;
                }
                
                // Manejar la interacción
                if (windowGroup && windowGroup.userData.slideWindow) {
                    if (specificPanel) {
                        // Click en panel específico
                        const panelNumber = specificPanel.replace('slidingPanel', '');
                        
                        if (windowGroup.userData.slidePanel) {
                            windowGroup.userData.slidePanel(panelNumber, 'toggle');
                        }
                        
                        // Efecto visual en el panel específico
                        this.flashPanel(windowGroup.getObjectByName(specificPanel));
                    } else {
                        // Click general en la ventana
                        windowGroup.userData.slideWindow('toggle');
                        
                        // Efecto visual general
                        this.flashWindow(windowGroup);
                    }
                }
            }
        }
    }

    onWindowHover(event) {
        // Calcular coordenadas del mouse para hover effects
        const rect = this.renderer.domElement.getBoundingClientRect();
        this.mouse.x = ((event.clientX - rect.left) / rect.width) * 2 - 1;
        this.mouse.y = -((event.clientY - rect.top) / rect.height) * 2 + 1;
        
        // Cambiar cursor si está sobre una ventana interactiva
        this.raycaster.setFromCamera(this.mouse, this.camera);
        
        if (this.productMesh) {
            const intersects = this.raycaster.intersectObjects(this.productMesh.children, true);
            
            if (intersects.length > 0) {
                let clickedObject = intersects[0].object;
                let isInteractive = false;
                let hoverInfo = '';
                
                while (clickedObject.parent) {
                    // Verificar si es un panel específico
                    if (clickedObject.name === 'slidingPanel1') {
                        isInteractive = true;
                        hoverInfo = 'Hoja Izquierda - Se desliza encima de la derecha';
                        break;
                    } else if (clickedObject.name === 'slidingPanel2') {
                        isInteractive = true;
                        hoverInfo = 'Hoja Derecha - Se desliza encima de la izquierda';
                        break;
                    } else if (clickedObject.parent.userData && clickedObject.parent.userData.slideWindow) {
                        isInteractive = true;
                        hoverInfo = 'Ventana corredera - Click para interactuar';
                        break;
                    }
                    clickedObject = clickedObject.parent;
                }
                
                // Cambiar cursor y tooltip
                this.renderer.domElement.style.cursor = isInteractive ? 'pointer' : 'default';
                this.renderer.domElement.title = isInteractive ? hoverInfo : '';
            } else {
                this.renderer.domElement.style.cursor = 'default';
                this.renderer.domElement.title = '';
            }
        }
    }

    flashWindow(windowGroup) {
        // Efecto visual rápido al hacer click
        const originalOpacity = windowGroup.children[0]?.material?.opacity || 1;
        
        if (windowGroup.children[0]?.material) {
            windowGroup.children[0].material.opacity = 0.5;
            setTimeout(() => {
                windowGroup.children[0].material.opacity = originalOpacity;
            }, 150);
        }
    }

    generateProduct() {
        // Limpiar producto anterior
        if (this.productMesh) {
            this.scene.remove(this.productMesh);
            
            // Función recursiva para limpiar geometrías y materiales
            const disposeObject = (obj) => {
                if (obj.geometry) {
                    obj.geometry.dispose();
                }
                if (obj.material) {
                    if (Array.isArray(obj.material)) {
                        obj.material.forEach(mat => mat.dispose());
                    } else {
                        obj.material.dispose();
                    }
                }
                // Limpiar hijos recursivamente
                if (obj.children) {
                    obj.children.forEach(child => disposeObject(child));
                }
            };
            
            disposeObject(this.productMesh);
        }

        // Preparar parámetros con scene y renderer para HDRI
        const parametersWithHDRI = {
            ...this.parameters,
            scene: this.scene,        // Para configuración HDRI
            renderer: this.renderer,  // Para PMREM generation
            hdriLoaders: this.hdriLoaders  // Loaders HDRI
        };

        // Debug: verificar parámetros que se pasan al modelo
        console.log('📦 Parameters passed to model:', {
            color: parametersWithHDRI.color,
            frameColor: parametersWithHDRI.frameColor,
            aluminumColor: parametersWithHDRI.aluminumColor,
            texturePath: parametersWithHDRI.texturePath
        });

        // Generar nuevo producto basado en tipo
        // Usar modelos especializados
        switch (this.productType) {
            case 'window':
                this.productMesh = this.models.window.generate(parametersWithHDRI);
                break;
            case 'door':
                this.productMesh = this.models.door.generate(parametersWithHDRI);
                break;
            case 'furniture':
                this.productMesh = this.models.furniture.generate(parametersWithHDRI);
                break;
            default:
                this.productMesh = this.models.window.generate(parametersWithHDRI);
        }

        if (this.productMesh) {
            this.scene.add(this.productMesh);
            
            // Solo ajustar cámara en primera carga, preservar en actualizaciones
            const isUpdate = this.cameraInitialized;
            this.fitCameraToProduct(isUpdate);
        }
    }

    // Métodos de generación movidos a archivos especializados
    // Ver: WindowModel.js, DoorModel.js, FurnitureModel.js











    // Actualizar parámetros en tiempo real
    updateParameter(key, value) {
        this.parameters[key] = value;
        
        // Sincronizar color principal con frameColor (siempre cuando se cambie 'color')
        if (key === 'color') {
            this.parameters.frameColor = value;
        }
        
        // Si es un cambio de color y ya existe el modelo, actualizar solo los colores
        if (['color', 'frameColor', 'glassColor'].includes(key) && this.productMesh && this.productMesh.userData.updateColors) {
            const parametersWithHDRI = {
                ...this.parameters,
                scene: this.scene,
                renderer: this.renderer
            };
            this.productMesh.userData.updateColors(parametersWithHDRI);
        } else {
            // Para otros cambios, regenerar el producto completo
            this.generateProduct();
        }
        
        // Dispatch evento personalizado para actualizar precios
        this.container.dispatchEvent(new CustomEvent('parametersChanged', {
            detail: { 
                parameters: this.parameters,
                productType: this.productType
            }
        }));
    }

    // Actualizar múltiples parámetros
    updateParameters(newParams) {
        
        this.parameters = { ...this.parameters, ...newParams };
        
        // Sincronizar color principal con frameColor si 'color' está en los nuevos parámetros
        if (newParams.color && !newParams.frameColor) {
            this.parameters.frameColor = newParams.color;
        }
        
        // Verificar si solo son cambios de color (sin cambios de textura)
        const colorKeys = ['color', 'frameColor', 'glassColor'];
        const textureKeys = ['texturePath'];
        const onlyColorChanges = Object.keys(newParams).every(key => colorKeys.includes(key)) && 
                                !Object.keys(newParams).some(key => textureKeys.includes(key));
        

        
        if (onlyColorChanges && this.productMesh && this.productMesh.userData.updateColors) {
            const parametersWithHDRI = {
                ...this.parameters,
                scene: this.scene,
                renderer: this.renderer
            };
            this.productMesh.userData.updateColors(parametersWithHDRI);
        } else {
            // Para otros cambios, regenerar el producto completo
            this.generateProduct();
        }
        
        this.container.dispatchEvent(new CustomEvent('parametersChanged', {
            detail: { 
                parameters: this.parameters,
                productType: this.productType
            }
        }));
    }

    fitCameraToProduct(preserveCamera = false) {
        if (!this.productMesh) {
            return;
        }

        const box = new THREE.Box3().setFromObject(this.productMesh);
        const size = box.getSize(new THREE.Vector3());
        const center = box.getCenter(new THREE.Vector3());
        const maxDim = Math.max(size.x, size.y, size.z);
        
        // Solo ajustar la cámara en la primera carga, no en actualizaciones
        if (!preserveCamera && !this.cameraInitialized) {
            // Distancia más cercana para mejor vista inicial
            const distance = Math.max(maxDim * 0.5, 1.4);
            
            // Posicionar cámara frente a frente del modelo (vista frontal)
            this.camera.position.set(0, center.y, distance);
            this.camera.lookAt(center.x, center.y, center.z);
            this.controls.target.copy(center);
            this.cameraInitialized = true;
        } else {
            // Solo actualizar el target si es necesario, manteniendo la posición actual
            this.controls.target.copy(center);
        }
        
        this.controls.update();
    }

    animate() {
        this.animationId = requestAnimationFrame(() => this.animate());
        this.controls.update();
        this.renderer.render(this.scene, this.camera);
    }

    // Obtener configuración actual para guardar
    getConfiguration() {
        return {
            productType: this.productType,
            parameters: { ...this.parameters },
            timestamp: Date.now()
        };
    }

    // Cargar configuración
    loadConfiguration(config) {
        if (config.productType !== this.productType) {
            return;
        }
        
        this.parameters = { ...this.parameters, ...config.parameters };
        this.generateProduct();
    }

    // Tomar captura
    screenshot(width = 800, height = 600) {
        const originalSize = this.renderer.getSize(new THREE.Vector2());
        
        this.renderer.setSize(width, height);
        this.camera.aspect = width / height;
        this.camera.updateProjectionMatrix();
        
        this.renderer.render(this.scene, this.camera);
        const dataURL = this.renderer.domElement.toDataURL();
        
        // Restaurar tamaño original
        this.renderer.setSize(originalSize.x, originalSize.y);
        this.camera.aspect = originalSize.x / originalSize.y;
        this.camera.updateProjectionMatrix();
        
        return dataURL;
    }

    dispose() {
        if (this.animationId) {
            cancelAnimationFrame(this.animationId);
        }

        if (this.controls) {
            this.controls.dispose();
        }

        if (this.productMesh) {
            this.scene.remove(this.productMesh);
            
            // Función recursiva para limpiar geometrías y materiales
            const disposeObject = (obj) => {
                if (obj.geometry) {
                    obj.geometry.dispose();
                }
                if (obj.material) {
                    if (Array.isArray(obj.material)) {
                        obj.material.forEach(mat => mat.dispose());
                    } else {
                        obj.material.dispose();
                    }
                }
                // Limpiar hijos recursivamente
                if (obj.children) {
                    obj.children.forEach(child => disposeObject(child));
                }
            };
            
            disposeObject(this.productMesh);
        }

        if (this.renderer) {
            this.renderer.dispose();
        }
    }

    // Métodos de zoom personalizados
    zoomIn(factor = 0.8) {
        const currentDistance = this.camera.position.length();
        const newDistance = currentDistance * factor;
        
        // Mantener dirección, cambiar distancia
        const direction = this.camera.position.clone().normalize();
        this.camera.position.copy(direction.multiplyScalar(newDistance));
        this.controls.update();
    }

    zoomOut(factor = 1.25) {
        const currentDistance = this.camera.position.length();
        const newDistance = currentDistance * factor;
        
        const direction = this.camera.position.clone().normalize();
        this.camera.position.copy(direction.multiplyScalar(newDistance));
        this.controls.update();
    }

    setZoomLevel(level) {
        // level: 1 = normal, 2 = zoom out, 0.5 = zoom in
        if (!this.productMesh) return;
        
        const box = new THREE.Box3().setFromObject(this.productMesh);
        const size = box.getSize(new THREE.Vector3());
        const center = box.getCenter(new THREE.Vector3());
        const maxDim = Math.max(size.x, size.y, size.z);
        
        const baseDistance = Math.max(maxDim * 2, 4);
        const distance = baseDistance * level;
        
        this.camera.position.set(distance * 0.8, distance * 0.6, distance * 0.8);
        this.camera.lookAt(center.x, center.y, center.z);
        this.controls.target.copy(center);
        this.controls.update();
    }

    resetZoom() {
        this.fitCameraToProduct(false);
    }
}

// Función global para crear configurador
window.createParametricProduct3D = function(containerId, productType, initialParams = {}) {
    return new ParametricProduct3D(containerId, productType, initialParams);
};

export { ParametricProduct3D };