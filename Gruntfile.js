module.exports = function (grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        sass: {
            dist: {
                files: {
                    'app/css/styles.css': 'app/scss/styles.scss'
                }
            }
        },
        watch: {
            css: {
                files: '**/*.scss',
                tasks: ['sass']
            }
        },
        concat: {
            options: {
                separator: '\r\n'
            },
            dist: {
                files: {
                    'dist/assets/js/app.js': [
                        "assets/js/bowser.js",
                        "assets/js/util.js",
                        "assets/js/whiteLists.js",
                        "assets/js/blackLists.js",
                        "assets/js/scripts.js",
                        "assets/js/casino.js"
                    ],
                    'dist/assets/js/vendors.js': [
                        "assets/js/jquery.min.js",
                        "assets/js/pace.js",
                        "assets/js/jquery.modal.min.js",
                        "assets/js/jquery.nice-select.min.js",
                        "assets/js/select2.min.js"
                    ],
                    'dist/assets/css/app.css': [
                        "assets/css/select2.min.css",
                        "assets/css/jquery.modal.min.css",
                        "assets/css/nice-select.css",
                        "assets/css/casino.css",
                        "assets/css/style.css",
                        "assets/css/kultura.css"
                    ]

                }
            }
        },
        copy: {
            dist: {
                files: [
                    // includes files within path
                    {expand: true, src: ['assets/fonts/*'], flatten: true, dest: 'dist/assets/fonts', filter: 'isFile'},
                    {expand: true, src: ['assets/css/kaltura-fonts.css'], flatten: true, dest: 'dist/assets/css', filter: 'isFile'},
                    {
                        expand: true,
                        src: ['assets/images/*'],
                        flatten: true,
                        dest: 'dist/assets/images',
                        filter: 'isFile'
                    },
                    {expand: true, src: ['assets/sound/*'], flatten: true, dest: 'dist/assets/sound', filter: 'isFile'},
                    {
                        src: 'index-dist.php',
                        dest: 'dist/',
                        expand: true
                    },
                    {
                        src: 'ajax.php',
                        dest: 'dist/',
                        expand: true
                    },
                    {
                        src: 'app.php',
                        dest: 'dist/',
                        expand: true
                    },
                    {
                        src: 'cx_conditions.csv',
                        dest: 'dist/',
                        expand: true
                    },
                    {
                        src: 'functions.php',
                        dest: 'dist/',
                        expand: true
                    },
                    {
                        src: 'Log.class.php',
                        dest: 'dist/',
                        expand: true
                    },
                    {
                        src: 'res/**',
                        dest: 'dist/',
                        expand: true
                    },
                    {
                        src: 'logs/*',
                        dest: 'dist/',
                        expand: true
                    },
                    {
                        src: 'inc/**',
                        dest: 'dist/',
                        expand: true
                    }
                ],
            },
        },
        clean: {
            options: {
                force: true
            },
            dist: ['dist/']
        },
        rename: {
            dist: {
                files: [
                    {src: ['dist/index-dist.php'], dest: 'dist/index.php'}
                ]
            }
        },
        uglify: {
            dist: {
                files: [{
                    'dist/assets/js/app.js': ['dist/assets/js/app.js']
                }],
                options: {
                    mangle: false
                }
            }
        },
        compress: {
            main: {
                options: {
                    archive: 'version.zip'
                },
                files: [
                    {expand: true, cwd: 'dist/', src: ['**'], dest: '/'} // makes all src relative to cwd
                ]
            }
        },
        cssmin: {
            options: {
                mergeIntoShorthands: false,
                roundingPrecision: -1
            },
            target: {
                files: {
                    'dist/assets/css/app.css': [ 'dist/assets/css/app.css']
                }
            }
        },
        obfuscator: {
            options: {
                // global options for the obfuscator
            },
            task1: {
                options: {
                    // options for each sub task
                },
                files: {
                    'dist/assets/js/app.js': ['dist/assets/js/app.js']
                }
            }
        }

    });

    grunt.loadNpmTasks('grunt-contrib-rename');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-envpreprocess');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-compress');
    grunt.loadNpmTasks('grunt-contrib-obfuscator');

    grunt.registerTask('prod', ['clean:dist', 'concat:dist', 'copy:dist', 'rename:dist', 'uglify:dist', 'cssmin', 'obfuscator', 'compress']);
};




