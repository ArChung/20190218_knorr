var vm_index = null;

var template_index = {
    template: '#template_index',
    props: ['introAnimation'],
    data: function () {
        return {
            introAni: null,
            animal: vueDataAnimal,
            plant: vueDataPlants,
            elements: vueDataElements,
            
        }
    },
    created: function () {
        vm_index = this;
        
    },
    computed: {
        
    },
    mounted: function () {
        var t = this;
        if (t.introAnimation ) {
            t.initAnimation();
        }
        t.initAutoDemo();
    },
    methods: {
        initAnimation: function () {
            var man1 = new TimelineMax({
                repeat: -1
            });
            var man2 = new TimelineMax({
                repeat: -1
            });
            this.introAni = new TimelineMax();
            var into = this.introAni;
            var t = this;
            // var arr = new TimelineMax({repeat: -1,repeatDelay:1});

            man1.to('.man1', 13, {
                    marginLeft: -600,
                    ease: Sine.easeInOut
                })
                .set('.man1', {
                    scaleX: -1
                })
                .to('.man1', 13, {
                    marginLeft: 0,
                    ease: Sine.easeInOut
                })
                .set('.man1', {
                    scaleX: 1
                })

            man2.set('.man4', {
                    scaleX: -1
                })
                .to('.man4', 6, {
                    marginLeft: -150,
                    ease: Sine.easeInOut
                })
                .set('.man4', {
                    scaleX: 1
                })
                .to('.man4', 6, {
                    marginLeft: 0,
                    ease: Sine.easeInOut
                })

            // var $body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');
            // setTimeout(function () {
            //     // var target = $(el);
            //     console.log(4567489);
            //     $body.stop();
            //     $body.animate({
            //         scrollTop: 0
            //     }, 0);
            // }, 20)
            into.set('#menu', {
                    top: -80
                }).set('body,html', {
                    overflowY: 'hidden'
                })
                .set('#intro .txtWrap *', {
                    alpha: 0,
                    y: 50
                })
                .set('.clouds > *', {
                    marginTop: 100
                })
                .set('#index .titleWrap > .title', {
                    scale: 0.3,
                    alpha: 0
                })
                .set('#index .titleWrap > .aniEl', {
                    y: 50,
                    alpha: 0
                })
                .staggerTo('#intro .txtWrap *', 1, {
                    alpha: 1,
                    y: 0,
                    ease: Power2.easeOut
                }, .1)
                .call(function () {
                    into.pause();
                    $(window).on('touchstart click DOMMouseScroll mousewheel', t.playIntro);
                })
                .to('#mainPage', 2.2, {
                    marginTop: '-100vh',
                    ease: Power3.easeInOut
                }, 'move')
                .to('.c1', 3, {
                    marginTop: 0,
                    ease: Power3.easeInOut
                }, 'move+=.9')
                .to('.c2', 3, {
                    marginTop: 0,
                    ease: Power3.easeInOut
                }, 'move+=.3')
                .to('.c3', 3, {
                    marginTop: 0,
                    ease: Power3.easeInOut
                }, 'move+=.5')
                .set('body,html', {
                    overflowY: 'visible'
                }, '-=1')

                .to('#index .titleWrap .title', .6, {
                    alpha: 1,
                    scale: 1,
                    ease: Back.easeOut
                }, 'txt-=2.2')
                .staggerTo('#index .titleWrap .aniEl', .6, {
                    alpha: 1,
                    y: 0,
                    // ease: Back.easeOut
                }, .2, 'txt-=2')
                .to('#menu', .6, {
                    top: 0
                }, 'txt-=2')



            // arr.to('#index .arr',.6,{y:-20,x:10,rotation:'20deg'})
            // .to('#index .arr',.15,{y:-0,x:0,rotation:'0deg'})
            // .to('#index .arr',.2,{y:-20,x:5,rotation:'10deg'})
            // .to('#index .arr',.15,{y:5,x:0,rotation:'0deg'})
            // .to('#index .arr',.1,{y:-0,x:0,rotation:'0deg'})

        },
        playIntro: function () {
            var t = this;
            t.introAni.play();
            $(window).off('touchstart click DOMMouseScroll mousewheel', t.playIntro);
        },
        isPhone: function () {
            testExp = new RegExp('Android|webOS|iPhone|iPad|' +
                'BlackBerry|Windows Phone|' +
                'Opera Mini|IEMobile|Mobile',
                'i');
            return (testExp.test(navigator.userAgent))
        },
        initAutoDemo: function () {
            if (this.isPhone()) {
                $('.tagBtn').on('inview', function (event, isInView) {
                    var t = $(this);
                    if (isInView) {
                        setTimeout(function () {
                            t.addClass('active');
                            setTimeout(function () {
                                t.removeClass('active')
                            }, 1000)
                        }, 300)
                    }
                });
            } else {
                // this.startDemo();
            }
        },
        startDemo: function () {
            this.stopDemo();
            var t = this;
            t.demoTimer = setInterval(function () {
                var num = t.animal.ani_data.length + t.plant.data.length;
                t.demoIndex = Math.floor(Math.random() * num)
            }, 2000)
        },
        stopDemo: function () {
            this.demoIndex = -1;
            if (this.demoTimer) {
                clearInterval(this.demoTimer)
            }
        },
    }
}