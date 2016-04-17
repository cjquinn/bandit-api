module.exports = function(grunt) {
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		postcss: {
			options: {
                map: false,
                processors: [
                    require('autoprefixer')({
                        browsers: ['last 2 versions']
                    })
                ]
            },
            dist: {
                src: 'library/css/*.css'
            }
		},
		sass: {
			dist: {
                options: {
                    'sourcemap=none': ''
                },
				files: {
					'webroot/css/style.css': 'webroot/css/scss/style.scss'
				}
			}
		},
		watch: {
			css: {
				files: 'library/css/scss/**/*.scss',
				tasks: ['sass', 'postcss:dist']
			}
		}
	});

	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-postcss');
	grunt.registerTask('default',['sass', 'postcss:dist']);
}