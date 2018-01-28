require('dotenv').config();

module.exports = function(grunt) {

    var options = {
        pkg: grunt.file.readJSON('package.json')
    };

    options.php = {
        test: {
            options: {
                keepalive: true,
                open: true,
                port: 5000
            }
        }
    };

    options['sftp-deploy'] = {
        main:  {
            auth: {
                host: process.env.SFTP_HOST,
                port: process.env.SFTP_PORT,
                authKey: 'primary'
            },
            cache: 'sftp_cache.json',
            src: './cvc/',
            dest: '/wp-content/themes/cvc/',
            exclusions: [
                '.DS_Store',
                'cvc/img/*',
                'favicon.ico',
                'cvc/screenshot.png'
            ],
            serverSep: '/',
            localSep: '/',
            concurrency: 4,
            progress: true
        }
    };

    options.copy = {
        main: {
            files: [
                {
                    expand: true,
                    src: ['cvc/**'],
                    dest: process.env.LOCAL_THEME_DIRECTORY
                }
            ]
        }
    };

    options.sass = {
        dist: {
            options: {
                style: 'expanded'
            },
            files: {
                'cvc/style.css': 'style.sass'
            }
        }
    }


    options.watch = {
        options: {
            livereload: true
        },
        theme: {
            files: ['cvc/*.php', 'cvc/lib/*.php', 'cvc/js/*.js'],
            tasks: ['copy']
        },
        sassy: {
            files: 'style.sass',
            tasks: ['sass', 'copy']
        },
        homepageJS: {
            files: 'cvc/js/homepage/*.js',
            tasks: ['uglify', 'copy']
        }
    };

    options.uglify = {
        global: {
            files: {
                'cvc/js/global.min.js': [
                    'bower_components/foundation/js/foundation.js',
                    'bower_components/foundation/js/foundation/foundation.topbar.js',
                    'bower_components/imagelightbox/dist/imagelightbox.min.js'
                ]
            }
        }
    };

    grunt.initConfig(options);

    grunt.loadNpmTasks('grunt-php');
    grunt.loadNpmTasks('grunt-sftp-deploy');
    grunt.loadNpmTasks('grunt-concurrent');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.registerTask('default', ['watch']);
    grunt.registerTask('deploy', ['sftp-deploy']);
    grunt.registerTask('js', ['uglify', 'copy']);

};