module.exports = function (grunt) {
	var cfg = {
		src: '.',
		dest: '../../../public/asimov'
	};

	var javascriptFiles = [
		'<%= cfg.src %>/material/js/jquery-2.1.1.min.js',
		'<%= cfg.src %>/material/vendors/moment/moment.min.js',
		//'<%= cfg.src %>/material/vendors/nicescroll/jquery.nicescroll.min.js',
		'<%= cfg.src %>/material/vendors/auto-size/jquery.autosize.min.js',
		'<%= cfg.src %>/material/vendors/bootgrid/jquery.bootgrid.min.js',
		'<%= cfg.src %>/material/vendors/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js',
		'<%= cfg.src %>/material/vendors/bootstrap-growl/bootstrap-growl.min.js',
		'<%= cfg.src %>/material/vendors/bootstrap-select/bootstrap-select.min.js',
		'<%= cfg.src %>/material/vendors/bootstrap-wizard/jquery.bootstrap.wizard.min.js',
		'<%= cfg.src %>/material/vendors/chosen/chosen.jquery.min.js',
		'<%= cfg.src %>/material/vendors/easypiechart/jquery.easypiechart.min.js',
		'<%= cfg.src %>/material/vendors/farbtastic/farbtastic.min.js',
		'<%= cfg.src %>/material/vendors/fileinput/fileinput.min.js',
		'<%= cfg.src %>/material/vendors/flot/jquery.flot.min.js',
		'<%= cfg.src %>/material/vendors/flot/jquery.flot.orderBar.js',
		'<%= cfg.src %>/material/vendors/flot/jquery.flot.pie.min.js',
		'<%= cfg.src %>/material/vendors/flot/jquery.flot.resize.min.js',
		'<%= cfg.src %>/material/vendors/flot/jquery.flot.tooltip.js',
		'<%= cfg.src %>/material/vendors/flot/plugins/curvedLines.js',
		'<%= cfg.src %>/material/vendors/fullcalendar/fullcalendar.min.js',
		'<%= cfg.src %>/material/vendors/fullcalendar/lib/moment.min.js',
		'<%= cfg.src %>/material/vendors/fullcalendar/lib/lang-all.js',
		'<%= cfg.src %>/material/vendors/input-mask/input-mask.min.js',
		'<%= cfg.src %>/material/vendors/light-gallery/lightGallery.min.js',
		'<%= cfg.src %>/material/vendors/mediaelement/mediaelement-and-player.min.js',
		'<%= cfg.src %>/material/vendors/noUiSlider/jquery.nouislider.all.min.js',
		'<%= cfg.src %>/material/vendors/simpleWeather/jquery.simpleWeather.min.js',
		'<%= cfg.src %>/material/vendors/sparklines/jquery.sparkline.min.js',
		'<%= cfg.src %>/material/vendors/summernote/summernote.min.js',
		'<%= cfg.src %>/material/vendors/sweet-alert/sweet-alert.min.js',
		'<%= cfg.src %>/material/vendors/waves/waves.min.js',
		'<%= cfg.src %>/material/js/bootstrap.min.js',
		'<%= cfg.src %>/material/js/charts.js',
		'<%= cfg.src %>/material/js/functions.js',
		'<%= cfg.src %>/js/*.js'
	];

	var cssFiles = [
		"<%= cfg.dest %>/dist/asimov.css",
		'<%= cfg.src %>/material/vendors/material-icons/material-design-iconic-font.min.css',
		'<%= cfg.src %>/material/vendors/animate-css/animate.min.css',
		'<%= cfg.src %>/material/vendors/bootgrid/jquery.bootgrid.css',
		'<%= cfg.src %>/material/vendors/farbtastic/farbtastic.css',
		'<%= cfg.src %>/material/vendors/fullcalendar/fullcalendar.css',
		'<%= cfg.src %>/material/vendors/light-gallery/lightGallery.min.css',
		'<%= cfg.src %>/material/vendors/mediaelement/mediaelementplayer.css',
		'<%= cfg.src %>/material/vendors/noUiSlider/jquery.nouislider.min.css',
		'<%= cfg.src %>/material/vendors/socicon/socicon.min.css',
		'<%= cfg.src %>/material/vendors/summernote/summernote.css',
		'<%= cfg.src %>/material/vendors/sweet-alert/sweet-alert.min.css',
		'<%= cfg.src %>/material/vendors/chose/chosen.css'
	];

	// Project configuration.
	grunt.initConfig({
		cfg: cfg,
		pkg: grunt.file.readJSON('package.json'),
		less: {
			development: {
				options: {
					paths: ["css"]
				},
				files: {
					"<%= cfg.dest %>/dist/asimov.css": "less/asimov.less"
				},
				cleancss: true
			}
		},
		concat: {
			ccs: {
				options: {
					separator: ''
				},
				src: cssFiles,
				dest: '<%= cfg.dest %>/dist/asimov.css'
			}
		},
		cssmin: {
			production: {
				files: [{
					expand: true,
					cwd: '<%= cfg.dest %>/dist',
					src: ['asimov.css'],
					dest: '<%= cfg.dest %>/dist',
					ext: '.min.css'
				}]
			}
		},
		csssplit: {
			production: {
				src: ['<%= cfg.dest %>/dist/asimov.min.css'],
				dest: '<%= cfg.dest %>/dist/asimov.min.css',
				options: {
					maxSelectors: 4095,
					suffix: '.'
				}
			}
		},
		uglify: {
			production: {
				options: {
					compress: true,
					mangle: true,
					sourceMap: true,
					sourceMapName: '<%= cfg.dest %>/dist/asimov.min.map'
				},
				files: {
					'<%= cfg.dest %>/dist/asimov.min.js': javascriptFiles
				}
			},
			development: {
				options: {
					mangle: false,
					compress: false,
					beautify: true
				},
				files: {
					'<%= cfg.dest %>/dist/asimov.min.js': javascriptFiles
				}
			}
		},
		watch: {
			styles: {
				files: [javascriptFiles + cssFiles], // which files to watch
				tasks: ['development'],
				options: {
					nospawn: true
				}
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
	grunt.registerTask('development', ['less', 'concat', 'cssmin', 'csssplit', 'uglify:development']);
	grunt.registerTask('default', ['less', 'concat', 'cssmin', 'csssplit', 'uglify:production']);

};
