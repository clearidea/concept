module.exports = function(grunt) {

   // Project configuration.
   grunt.initConfig({
     pkg: grunt.file.readJSON('package.json'),
     uglify: {
        options: {
           banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
        },
        build: {
           src: 'src/<%= pkg.name %>.js',
           dest: 'build/<%= pkg.name %>.min.js'
        }
     },
     phpdocumentor: {
        dist: {
           options: {
             directory : './src',
             target : 'doc'
           }
         }
      },
      phpunit: {
         classes: {
            dir: 'tests'
         },
         options: {
            bin: 'vendor/bin/phpunit',
            colors: true
         }
      }
   });

  // Load the plugin that provides the "uglify" task.
  grunt.loadNpmTasks('grunt-contrib-uglify');

  grunt.loadNpmTasks('grunt-phpdocumentor');

  grunt.loadNpmTasks('grunt-phpunit');

  // Default task(s).
  grunt.registerTask('default', ['uglify']);


};
