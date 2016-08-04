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

  options['ftp-deploy'] = {
    main: {
      auth: {
        host: process.env.PRIMARY_FTP_HOST,
        port: 21,
        username: process.env.PRIMARY_FTP_USERNAME,
        password: process.env.PRIMARY_FTP_PASSWORD
      },
      src: options.pkg.name,
      dest: options.pkg.name,
      exclusions: [
        'cvc/img/*',
        '.DS_Store',
        'favicon.ico',
        'cvc/screenshot.png'
      ],
      forceVerbose: true
    }
  }

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
  }

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
  }

  grunt.initConfig(options);

  grunt.loadNpmTasks('grunt-php');
  grunt.loadNpmTasks('grunt-ftp-deploy');
  grunt.loadNpmTasks('grunt-concurrent');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-uglify');

  grunt.registerTask('default', ['watch']);
  grunt.registerTask('deploy', ['ftp-deploy']);
  grunt.registerTask('js', ['uglify', 'copy']);

};