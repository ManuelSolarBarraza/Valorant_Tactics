# VALORANT TACTICS — v2: mapas, plantilla y roles competitivos

Proyecto académico Iplacex. PHP 8.x, MySQL 8.0 (probable compatibilidad MariaDB de XAMPP), HTML5, CSS3 nativo, JavaScript vanilla y patrón MVC. Sin Bootstrap ni Tailwind.

## SI YA LO INSTALASTE CON XAMPP: ACTUALIZA SIN BORRAR TUS DATOS

1. **Respalda** la base de datos: en `http://localhost/phpmyadmin` selecciona `valorant_tactics` → **Exportar** → **Rápido** → SQL → **Exportar**. Guarda el archivo fuera de `htdocs`.
2. En phpMyAdmin, **selecciona `valorant_tactics`** a la izquierda y entra en **Importar**. Selecciona `migracion_v2.sql` (NO `database.sql`) y pulsa **Continuar**. Hazlo **una sola vez**.
3. En `C:\xampp\htdocs` cambia el nombre de tu carpeta actual `valorant-tactics` a `valorant-tactics-respaldo` y **copia la nueva carpeta** `valorant-tactics` que viene en este ZIP en `htdocs`.
4. Abre `http://localhost/valorant-tactics/`. Comprueba el login, luego visita **Mapas**, **Plantilla**, **Scrims** y **Dashboard**. No hace falta reimportar `database.sql`.
5. Si un módulo falla, conserva la copia original y el respaldo SQL hasta resolverlo.

> ATENCIÓN: importar `database.sql` sobre tu instalación actual **destruiría y recrearía las tablas**. Solo se usa para una instalación nueva. Para la que ya tienes funcionando, usa `migracion_v2.sql`.

## INSTALACIÓN TOTALMENTE NUEVA

1. Copia la carpeta `valorant-tactics` a `htdocs` de XAMPP.
2. Importa **solo** `database.sql` en phpMyAdmin; crea las ocho tablas con las cuentas y las cuatro scrims demo.
3. Ajusta `config/database.php` si cambiaste usuario o contraseña MySQL.
4. Abre `http://localhost/valorant-tactics/`.

## CAMBIOS DE LA VERSIÓN 2

- **13 mapas estándar:** Abyss, Ascent, Bind, Breeze, Corrode, Fracture, Haven, Icebox, Lotus, Pearl, Split, Summit y Sunset.
- Rotación inicial competitiva **7 mapas** (Acto 6 de 2026, parche 13.08): Abyss, Bind, Corrode, Haven, Lotus, Summit y Sunset. La pantalla **Mapas** permite al Coach elegir los siete vigentes cuando cambien.
- Coach/Analista con permisos de administración; jugadores con estado **Titular, Suplente, Parche o Tryout**. Los cinco **roles competitivos de equipo** son Duelista, Iniciador, Flex, Smoker y Centinela. Son conceptos independientes.
- La tabla `roles_competitivos` eleva el esquema a **8 tablas**, cumpliendo el mínimo de seis establecido por la pauta; queda en forma relacional normalizada.
- `usuarios.id_rol_competitivo` almacena rol principal actual (nullable). `estadisticas_jugador.id_rol_competitivo` almacena el rol de **esa partida**, sin afectarse por promociones posteriores.
- El Coach puede editar estado y rol en **Plantilla**, y corregir roles competitivos históricos desde **Scrims → Ver**.
- En nueva scrim se registran separadamente: jugador, estado en partida, rol competitivo en partida, agente y métricas. Se proponen automáticamente el estado y rol de la plantilla, pero son editables.
- Dashboard muestra por defecto winrate de siete mapas activos, con opción de mostrar históricos, y compara jugadores filtrando por estado y rol competitivo de partida. «Mapas jugados» no se confunde con la cantidad de mapas activos.
- El catálogo de tácticas permite mapas de rotación e históricos, conservando fichas previas.

**Fuente de rotación al construir v2:** https://playvalorant.com/en-us/news/game-updates/valorant-patch-notes-13-08/ (13 oct 2026) y https://wiki.playvalorant.com/en-us/Maps (consultado 17 nov 2026). Los mapas cambian con nuevos actos: actualízalos desde «Mapas».

## Cuentas DEMO

- Coach: `coach@valorantteam.cl` / `Password123!`
- Jugador titular: `jugador@valorantteam.cl` / `Password123!`
- Tryout: `tryout@valorantteam.cl` / `Password123!`

## VERIFICACIONES RECOMENDADAS

1. Dashboard: 4 scrims, 2 victorias, 50% win rate y 7 mapas activos; activa «históricos» para ver los 13.
2. Plantilla: modifica el estado o rol de un jugador y vuelve a la scrim anterior: sus roles históricos deben mantenerse.
3. Scrims → Ver: comprueba que aparecen los roles competitivos de las 4 partidas demo.
4. Nueva scrim: verifica campos «Estado en esta scrim» y «Rol competitivo en esta scrim».
5. Mapas: asegúrate de ver 13, y que solo puedan guardarse exactamente 7 en rotación.
6. Tryout: intenta visitar una ruta de Coach: debe impedirlo.

## NOTAS DE USO REAL

- **Cambia las contraseñas demo** y elimina los datos de demostración antes de trabajar con información personal real. El sistema no incluye gestión de invitaciones ni recuperación de contraseñas.
- Usa HTTPS para publicar. No subas contraseñas DB a GitHub. En un hosting compartido con base existente, importa `database.sql` sin sus sentencias CREATE DATABASE/USE.
- No hemos verificado todavía esta nueva versión contra tu servidor XAMPP. La comprobación de sintaxis PHP no equivale a una prueba de integración SQL/browser.
- Datos históricos de usuarios no demo quedan sin rol competitivo automáticamente para no suponer su posición; se asignan en **Plantilla** y las partidas previas en **Scrims → Ver**.
- El término «Smoker» es un rol competitivo de equipo (no la categoría oficial de agente «Controlador»). «Flex» también es una función del equipo.
