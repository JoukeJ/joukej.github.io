module.exports = function (grunt) {
    var cfg = {
        src: '.',
        dest: '../../../public/backend'
    };

    // Project configuration.
    grunt.initConfig({
        cfg: cfg,
        pkg: grunt.file.readJSON('package.json'),
        less: {
            development: {
                options: {
                    paths: [""]
                },
                files: {
                    "<%= cfg.dest %>/css/ttc.css": [
                        "<%= cfg.src %>/less/backend.less"
                    ]
                },
                cleancss: true
            }
        },
        watch: {
            styles: {
                files: ['<%= cfg.src %>/less/**/*.less'], // which files to watch
                tasks: ['less'],
                options: {
                    nospawn: true
                }
            },
            js: {
                files: ['<%= cfg.src %>/js/*.js'], // which files to watch
                tasks: ['concat'],
                options: {
                    nospawn: true
                }
            },
        },
        concat: {
            js: {
                options: {
                    separator: ';'
                },
                src: [
                    '<%= cfg.src %>/node_modules/jquery/dist/jquery.js',
                    '<%= cfg.src %>/node_modules/bootstrap/dist/js/bootstrap.js',
                    '<%= cfg.src %>/js/*.js'
                ],
                dest: '<%= cfg.dest %>/js/backend.js'
            }
        }
    });

    // Load the plugin that provides the "less" task.
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-csssplit');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-concat');

    // Default task(s).
    grunt.registerTask('default', ['less', 'concat']);

};
