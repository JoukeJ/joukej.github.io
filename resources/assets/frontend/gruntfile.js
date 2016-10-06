module.exports = function (grunt) {
    var cfg = {
        src: '.',
        dest: '../../../public/frontend'
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
                    "<%= cfg.dest %>/css/smart.css": ["<%= cfg.src %>/less/smart.less"],
                    "<%= cfg.dest %>/css/feature.css": ["<%= cfg.src %>/less/feature.less"]
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
                    '<%= cfg.src %>/js/*.js'
                ],
                dest: '<%= cfg.dest %>/js/frontend.js'
            }
        },
        uglify: {
            production: {
                options: {
                    compress: true,
                    mangle: true,
                    sourceMap: true,
                    sourceMapName: '<%= cfg.dest %>/js/frontend.min.map'
                },
                files: {
                    '<%= cfg.dest %>/js/frontend.min.js': '<%= cfg.dest %>/js/frontend.js'
                }
            }
        },
    });

    // Load the plugin that provides the "less" task.
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-csssplit');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-concat');

    // Default task(s).
    grunt.registerTask('default', ['less', 'concat', 'uglify:production']);

};
