module.exports = function(grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        copy: {
            main: {
                files: [{
                    expand: true,
                    cwd: 'bower/bootstrap/dist/fonts',
                    src: ['*'],
                    dest: 'fonts/',
                    filter: 'isFile'
                }, {
                    expand: true,
                    cwd: 'bower/etalab-assets/fonts',
                    src: ['*'],
                    dest: 'fonts/',
                    filter: 'isFile'
                }, {
                    expand: true,
                    cwd: 'bower/etalab-assets/img/flags',
                    src: ['*.png'],
                    dest: 'img/flags',
                    filter: 'isFile'
                }, {
                    expand: true,
                    cwd: 'bower/etalab-assets/img',
                    src: ['*'],
                    dest: 'img/',
                    filter: 'isFile'
                }, {
                    expand: true,
                    cwd: 'bower/etalab-assets/data',
                    src: ['main_topics.json'],
                    dest: '.',
                    filter: 'isFile'
                }]
            }
        },
        less: {
            options: {
                paths: [
                    'bower/etalab-assets/less',
                    'bower/bootstrap/less'
                ]
            },
            dev: {
                files: {
                    'css/etalab-mediawiki.css': ['less/etalab-mediawiki.less']
                }
            },
            prod: {
                options: {
                    yuicompress: true
                },
                files: {
                    'css/etalab-mediawiki.min.css': ['less/etalab-mediawiki.less']
                }
            }
        },
        uglify: {
            options: {
                banner: [
                    '/*! <%= pkg.name %> v<%= grunt.template.today("yyyy-mm-dd HH:MM") %>',
                    ' * http://www.etalab2.fr',
                    ' * Copyright (c) <%= grunt.template.today("yyyy") %> Etalab',
                    ' * GNU AFFERO GENERAL PUBLIC LICENSE',
                    ' */',
                    ''
                ].join('\n')
            },
            build: {
                files: {
                    'js/etalab-mediawiki.min.js': [
                        'bower/jquery/jquery.js',
                        'bower/bootstrap/dist/js/bootstrap.js',
                        'bower/typeahead.js/dist/typeahead.js',
                        'bower/jquery.cookie/jquery.cookie.js',
                        'bower/jquery.dotdotdot/src/js/jquery.dotdotdot.js',
                        'bower/swig/index.js',
                        'bower/etalab-assets/js/etalab-site.js'
                    ],
                    'js/etalab-mediawiki-legacy.min.js': [
                        'bower/jquery-legacy/index.js',
                        'bower/bootstrap/dist/js/bootstrap.js',
                        'bower/typeahead.js/dist/typeahead.js',
                        'bower/jquery.cookie/jquery.cookie.js',
                        'bower/jquery.dotdotdot/src/js/jquery.dotdotdot.js',
                        'bower/swig/index.js',
                        'bower/etalab-assets/js/etalab-site.js',],
                    'js/modernizr.min.js': [
                        'bower/modernizr/modernizr.js',
                        'bower/respond/dest/respond.src.js'
                    ]
                }
            }
        },
        watch: {
            javascript: {
                files: ['js/etalab-mediawiki.js'],
                tasks: ['uglify']
            },
            style: {
                files: ['less/etalab-mediawiki.less'],
                tasks: ['less']
            },
        }
    });

    // Load libs
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-less');

    // Register the default tasks
    grunt.registerTask('default', ['copy', 'less', 'uglify']);

};
