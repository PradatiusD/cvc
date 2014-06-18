
module.exports = (grunt) ->
	grunt.initConfig(

		php:
			test:
				options:
					keepalive: true
					open: true
					port: 5000

		'ftp-deploy':
			dev:
				auth:
					host: 'pradadesigners.com'
					port: 21
					authKey: 'key1'
				src: 'cvc'
				dest: 'wp-content/themes/cvc'
				exclusions: [
					'cvc/lib/*'
					'cvc/img/*'
					'.DS_Store'
					'favicon.ico'
					'cvc/screenshot.png'
				]

		copy:
			main:
				files: [{
					expand: true
					src: ['cvc/**']
					dest: '../themes/'}
				]

		sass:                              
			dist:                            
				options:                       
					style: 'expanded'
				files:
					'cvc/style.css': 'style.sass'

		watch:
			theme:
				files: 'cvc/*.php'
				tasks: ['copy']
			sassy:
				files: 'style.sass'
				tasks: ['sass','copy']
			homepageJS:
				files: 'cvc/js/homepage/*.js'
				tasks: ['uglify','copy']

		uglify:
			global:
				files:
					'cvc/js/global.min.js': [
						'bower_components/foundation/js/foundation.js',
						'bower_components/foundation/js/foundation/foundation.topbar.js',
						'bower_components/imagelightbox/dist/imagelightbox.min.js'
					]
	)

	grunt.loadNpmTasks('grunt-php')
	grunt.loadNpmTasks('grunt-ftp-deploy')
	grunt.loadNpmTasks('grunt-concurrent')
	grunt.loadNpmTasks('grunt-contrib-copy')
	grunt.loadNpmTasks('grunt-contrib-sass')
	grunt.loadNpmTasks('grunt-contrib-watch')
	grunt.loadNpmTasks('grunt-contrib-uglify')
	grunt.registerTask('default', ['watch'])
	grunt.registerTask('deploy', ['sass','ftp-deploy'])
	grunt.registerTask('js', ['uglify','copy'])
