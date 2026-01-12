# TestLab

**TestLab** es una aplicación web diseñada para facilitar el registro, ejecución y seguimiento de pruebas de software en entornos de desarrollo. Su objetivo principal es mejorar la trazabilidad de los test y centralizar la información que, tradicionalmente, se gestiona de manera dispersa en hojas de cálculo o documentos.

Actualmente, muchos equipos carecen de un sistema unificado para documentar y controlar los resultados de las pruebas, lo que provoca:

* Falta de organización
* Dificultad para identificar errores recurrentes
* Problemas de comunicación entre desarrolladores, testers y gestores de proyectos

**TestLab** pretende solucionar estas necesidades mediante un sistema estructurado que permite:

* Registrar de forma uniforme los casos de prueba y sus resultados
* Asociar cada prueba a una versión o proyecto concreto
* Consultar el historial de ejecuciones y su trazabilidad
* Generar estadísticas globales sobre el estado de las pruebas
* Mejorar la comunicación interna y la calidad final del software

Los principales beneficiarios son equipos de desarrollo, testers, gestores de proyectos y clientes que deseen una visión clara y actualizada del estado de las pruebas.

## Diagrama de flujo previsto

```mermaid
graph TD;
classDef default font-size:12px;
    A[Login] --> B{¿Login correcto?};
    B -- No --> A;
    B -- Sí --> C((fa:fa-gauge Dashboard));
    C -- Desconexión --> A
    C <--> D[fa:fa-folder Proyectos ];
    C <--> F[fa:fa-chart-bar Estadísticas ];
    C <--> G[fa:fa-user Usuarios ];
    D <--> H[Lista proyectos];
    I <--> H2[Ficha proyecto];
    H2 <--> M1[CRUD versiones];
    H2 <--> M2[CRUD Casos de prueba];
    M2 <--> M3[CRUD Tests]
    H <--> I[CRUD proyectos];
    F <--> J1[Generales];
    F <--> J2[Proyecto/Versión];
    F <--> J3[Usuario];
    G <--> P[Lista usuarios];
    P <--> P1[CRUD usuarios];
```

## Diagrama E-R previsto

```mermaid
erDiagram
USUARIOS ||--o{ PROYECTOS : "tiene"
USUARIOS ||--o{ EJECUCIONES : "realiza"
PROYECTOS ||--o{ VERSIONES : "posee"
PROYECTOS ||--o{ CASOS_PRUEBA : "define"
VERSIONES ||--o{ EJECUCIONES :"agrupa"
VERSIONES ||--o{ CASOS_PRUEBA :"tiene"
CASOS_PRUEBA ||--o{ EJECUCIONES : "se prueban en"

USUARIOS { 
    int id PK 
    string nombre 
    string email 
    string password 
    string rol 
} 
PROYECTOS { 
    int id PK 
    string nombre 
    string descripcion 
    int usuario_id FK 
} 
VERSIONES { 
    int id PK 
    string numero_version 
    date fecha_lanzamiento 
    string descripcion 
    int proyecto_id FK 
} 
CASOS_PRUEBA { 
    int id PK 
    string titulo 
    string objetivo 
    string condiciones 
    text pasos 
    string resultado_esperado 
    string rol 
    int proyecto_id FK 
} 
EJECUCIONES { 
    int id PK 
    int caso_prueba_id FK 
    int version_id FK 
    int usuario_id FK
    enum usuario_perfil
    string resultado 
    string mensaje 
    string datos_utilizados 
    string estado_error 
    string correccion 
    string observaciones 
    datetime fecha_ejecucion 
}
```