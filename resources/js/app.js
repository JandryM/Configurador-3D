import './bootstrap';
import './auth-sync';
import ApexCharts from 'apexcharts';

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

// Gráfico de líneas: Evolución de Proformas (últimos 6 meses)
if (document.getElementById('proformasLineChart')) {
    const optionsProformasLine = {
        chart: {
            type: 'line',
            height: 240,
            toolbar: { show: false },
            zoom: { enabled: false }
        },
        series: [
            {
                name: 'Creadas',
                data: window.dashboardProformasLine?.creadas || [],
                color: '#3b82f6'
            },
            {
                name: 'Aprobadas',
                data: window.dashboardProformasLine?.aprobadas || [],
                color: '#22c55e'
            },
            {
                name: 'Expiradas',
                data: window.dashboardProformasLine?.expiradas || [],
                color: '#ef4444'
            }
        ],
        xaxis: {
            categories: window.dashboardProformasLine?.meses || [],
            labels: {
                style: {
                    colors: '#64748b',
                    fontSize: '10px'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: '#64748b',
                    fontSize: '10px'
                },
                formatter: function (val) {
                    return Math.floor(val);
                }
            }
        },
        legend: {
            show: false
        },
        stroke: {
            width: 2,
            curve: 'smooth'
        },
        markers: {
            size: 4,
            hover: {
                size: 6
            }
        },
        dataLabels: {
            enabled: false
        },
        grid: {
            borderColor: '#e2e8f0',
            strokeDashArray: 3,
            padding: {
                top: 0,
                right: 0,
                bottom: 0,
                left: 0
            }
        },
        tooltip: {
            shared: true,
            intersect: false,
            y: {
                formatter: function (val) {
                    return val + ' proformas';
                }
            }
        }
    };
    const chartProformasLine = new ApexCharts(document.getElementById('proformasLineChart'), optionsProformasLine);
    chartProformasLine.render();
}

// Inicialización del gráfico donut (Estado de Órdenes)
if (document.getElementById('ordenesDonutChart')) {
    const optionsDonut = {
        chart: {
            type: 'donut',
            height: 200
        },
        series: window.dashboardDonutSeries || [],
        labels: window.dashboardDonutLabels || [],
        colors: ['#facc15', '#06b6d4', '#a78bfa', '#22c55e', '#ef4444'],
        legend: {
            show: false
        },
        dataLabels: {
            enabled: false
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '65%',
                    labels: {
                        show: true,
                        name: {
                            show: true,
                            fontSize: '11px',
                            color: '#64748b',
                            offsetY: 15
                        },
                        value: {
                            show: true,
                            fontSize: '20px',
                            fontWeight: 700,
                            color: '#0f172a',
                            offsetY: -8
                        },
                        total: {
                            show: true,
                            label: 'Total',
                            fontSize: '10px',
                            color: '#64748b',
                            formatter: function (w) {
                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                            }
                        }
                    }
                }
            }
        },
        stroke: {
            width: 2,
            colors: ['#fff']
        }
    };
    const chartDonut = new ApexCharts(document.getElementById('ordenesDonutChart'), optionsDonut);
    chartDonut.render();
}