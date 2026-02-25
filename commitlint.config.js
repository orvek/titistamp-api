module.exports = {
    extends: ["@commitlint/config-conventional"],
    rules: {
        "type-enum": [
            2,
            "always",
            [
                "feat", // Nueva funcionalidad
                "fix", // Corrección de bug
                "docs", // Documentación
                "style", // Formato, estilos (no afecta código)
                "refactor", // Refactorización
                "perf", // Mejora de rendimiento
                "test", // Tests
                "build", // Cambios en build system
                "ci", // Cambios en CI
                "chore", // Tareas de mantenimiento
                "revert", // Revertir cambios
            ],
        ],
    },
};
