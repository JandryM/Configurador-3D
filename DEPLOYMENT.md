# Configuración para Producción

## Cron Job Requerido
Agregar al crontab del servidor:
```bash
* * * * * cd /ruta/al/proyecto && php artisan schedule:run >> /dev/null 2>&1