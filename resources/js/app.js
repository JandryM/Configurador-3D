import './bootstrap';
import './auth-sync';

// Importar Three.js y hacerlo global
import * as THREE from 'three';
window.THREE = THREE;

// Importar nuestros módulos 3D esenciales
import './parametric-3d';          // CORE: Generación procedural de modelos 3D
import './simple-3d-viewer';       // FALLBACK: Sistema de respaldo

// Configuración global de la aplicación
window.QualityApp = {
    // Funciones de la aplicación cuando sean necesarias
};

console.log('✅ Three.js cargado:', typeof THREE !== 'undefined' ? 'SÍ' : 'NO');