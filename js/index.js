Vue.use(VueAwesomeSwiper)



var vm = null;

var app = new Vue({
    el: '#app',
    data: {
        pageChannel: 'mainPage',
        popChannel: '',
        introAnimation: true,
        recipeChannel: 0,
        introAni: null,
        htmlScollPosition: 0,
        formErrors: [],
        menuOpen: false,
        demoIndex: -1,
        demoTimer: null,
        GAPage: '',
        gaTimeout: null,
        userData: {
            name: '',
            phone: '',
            email: '',
            marriage: null,
            hasChild: null,
            child: [],
            agreeToSendMeInfo: true,
            agreeRule: true,
            score: 0,
            token: '',
        },
        ruleSwitch: {
            s1: true, //問答挑戰
            s2: true, //抽獎辦法
            s3: true, //貼文留言活動
            s4: false, //中獎名單
            s5: false, //活動注意事項
            s6: false //蒐集個人資料聲明
        },
        qa: vueDataQA,
        animal: vueDataAnimal,
        plant: vueDataPlants,
        elements: vueDataElements,
        product_SwiperOption: {
            pagination: {
                el: '.swiper-pagination'
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev'
            },
            loop: true,
            on: {
                click: function (e) {
                    if($(e.target).hasClass('buyBtn')){
                        vm.onProBuy();
                    }
                    if($(e.target).hasClass('cloz')){
                        vm.onProBuyCloz();
                    }
                }
            }
        },
        products: vueDataProducts,
        showTopBtn: false,
        windowScrollTop: 0,
    },
    created: function () {
        vm = this;
    },
    computed: {
        product_Swiper: function () {
            return this.$refs.product_Swiper.swiper
        }
    },
    mounted: function () {
        var t = this;
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }

        if (t.introAnimation) {
            t.initAnimation();
        }

        t.initAutoDemo();

        $('.product_Swiper').on('inview', function (event, isInView) {
            setTimeout(function () {
                t.playSwiperAni()
            }, 200);
        });

        $('body').on('click', '.gaBtn', function (e) {
            var tt = ($(e.target).hasClass('gaBtn')) ? $(e.target) : $(e.target).closest('.gaBtn');
            t.ga_btn(tt.attr('data-ga'));
        });

        $('.gaPage').on('inview', function (e, isInView) {
            if (t.gaTimeout) {
                clearTimeout(t.gaTimeout);
                t.gaTimeout = null;
                // console.log('cancel');
            }

            t.gaTimeout = setTimeout(() => {
                t.GAPage = $(e.target).attr('data-ga');
            }, 10);
        });

        // console.log(this.getUrlParameter("page"));
        if (this.getUrlParameter("page") == 'rule') {
            console.log(this.introAni);
            if (this.introAni) {
                this.introAni.progress(1, false);
            }
            this.pageChannel = 'rule';
        }

        t.initScrollEvent();
    },
    watch: {
        demoIndex: function (val) {
            $('.tagBtn').removeClass('active');
            if (val != -1) {
                $('.tagBtn').eq(val).addClass('active');
            }
        },
        popChannel: function (val) {
            if (val == '') {
                this.unLockScroll();
            } else {
                this.lockScroll();
            }
        },
        pageChannel: function (val) {
            this.goTop();
        },
        menuOpen: function (val) {
            if (this.introAni) {
                this.introAni.progress(1, false);
            }
        },
        GAPage: function (val) {
            this.ga_page(val);
        },
        windowScrollTop: function (val) {
            this.showTopBtn = (val > 250) ? true : false;
        },
    },
    methods: {
        // get url for rule
        getUrlParameter: function (sParam) {
            var sPageURL = decodeURIComponent(window.location.search.substring(1)),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;


            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : sParameterName[1];
                }
            }
        },
        // GA
        ga_page: function (inv) {
            // console.log('GA_PAGE: ', inv);
            ga('send', 'pageview', inv);
        },
        ga_btn: function (inv) {
            // console.log('GA_BUTTON: ', inv);
            ga('send', 'event', 'btn', inv);
        },
        plantsPopupSeeMore(id) {
            this.pageChannel = "product";
            this.popChannel = "";
            this.pageScrollAni("#product_Swiper");
            this.product_Swiper.slideTo(this.plant.data[id].seeMoreProductId);
            this.ga_btn('index_' + this.plant.data[id].enName + '_more')
        },
        
        // swiper
        initSwiper: function () {
            // var swiper= this.product_Swiper.slides[0];
            var tl = new TimelineMax();
            tl.set($(this.product_Swiper.slides).find('.sub'), {
                autoAlpha: 0
            })
        },
        playSwiperAni: function () {
            if (this.pageChannel != 'product') {
                return;
            }
            var swiper = this.product_Swiper.slides[this.product_Swiper.activeIndex];
            this.initSwiper();
            var tl = new TimelineMax();
            tl.set($(swiper).find('.sub1'), {
                    scale: .5,
                    rotation: 20
                })
                .set($(swiper).find('.sub2'), {
                    scale: .5,
                    rotation: -20
                })
                .set($(swiper).find('.sub3'), {
                    scale: .8,
                    y: 30
                })
                .staggerTo($(swiper).find('.sub'), 0.6, {
                    scale: 1,
                    rotation: 0,
                    autoAlpha: 1,
                    y: 0,
                    ease: Back.easeOut
                }, .1)

            this.ga_btn('product_' + (this.product_Swiper.activeIndex + 1))

        },
        onProBuy: function () {
            this.ga_btn('product_buy');
            $('#product_Swiper .buyBtn').addClass('hide');
            simpleShow($('#product_Swiper .shopBtns'))
            // $('#product_Swiper .shopBtns').removeClass('hide');
        },
        onProBuyCloz: function () {
            $('#product_Swiper .shopBtns').addClass('hide');
            simpleShow($('#product_Swiper .buyBtn'))
        },

        nextRecipe: function (id) {
            var t = this;
            this.ga_btn('recipe_' + (t.recipeChannel + 1) + '_next')
            t.popChannel = 'temp';
            t.recipeChannel = (t.recipeChannel >= 7) ? 0 : t.recipeChannel + 1
            setTimeout(function () {
                t.popChannel = 'recipe';
            }, 100);
            this.ga_btn('recipe_' + (t.recipeChannel + 1))
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
        onMenu: function (goto) {
            this.menuOpen = false;
            switch (goto) {
                case 'map':
                    this.pageChannel = 'mainPage';
                    this.pageScrollAni('#fieldContainer')
                    break;
                case 'qa':
                    this.pageChannel = 'mainPage';
                    this.reQA();
                    break;
                case 'plant':
                    this.pageChannel = 'mainPage';
                    this.pageScrollAni('#infoContainer')
                    break;
                case 'product':
                    this.pageChannel = 'product';
                    var t = this;
                    t.initSwiper();
                    this.ga_page('P4_product')
                    break;
                case 'rule':
                    this.pageChannel = 'rule';
                    this.pageScrollAni('#rule')
                    this.ga_page('P5_rule')
                    break;

                case 'winner':
                    this.pageChannel = 'rule';
                    this.ruleSwitch.s4 = true;
                    this.pageScrollAni('#winner')
                    this.ga_page('P5_rule')
                    break;
            }
        },
        pageScrollAni: function (el) {
            var $body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');
            setTimeout(function () {
                var target = $(el);
                $body.animate({
                    scrollTop: target.offset().top - 100
                }, 600);
            }, 10)
        },
        onPopupBg: function () {
            if (this.popChannel == "form" || this.popChannel == "qa") {} else {
                this.popChannel = "";
            }
        },
        goTop: function () {
            var $body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');
            $body.animate({
                scrollTop: 0
            }, 300);
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
        reQA: function () {
            this.qa.state = 'question';
            this.userData.score = 0;
            this.qa.userAnswerNow = -1;
            this.qa.index = 0;
            this.popChannel = 'qa';
            this.ga_page('P2_quiz')
        },
        nextQuestion: function () {
            this.ga_btn('quiz_q' + (this.qa.index + 1) + '_next');

            if (this.qa.answer[this.qa.index] == this.qa.userAnswerNow) {
                this.userData.score += 1;
            }

            if (this.qa.index < 4) {
                this.qa.userAnswerNow = -1;
                this.qa.index += 1;
                this.qa.state = "question";
            } else {
                this.qa.state = 'result';
            }

        },
        onAnswer: function () {
            this.qa.state = "answer";
            this.ga_btn('quiz_q' + (this.qa.index + 1));

        },
        retry: function () {
            this.reQA();
            this.ga_btn('quiz_again');
        },
        fillForm: function () {
            this.popChannel = "form"
            this.ga_btn('quiz_fillin');
        },
        openAnimal: function (id) {
            this.animal.index = id;
            this.popChannel = 'animal';
            this.ga_btn('index_' + this.animal.ani_data[id].className)

        },
        openPlant: function (id) {
            this.plant.index = id;
            this.popChannel = 'plant';
            this.ga_btn('index_' + this.plant.data[id].enName)

        },
        openElement: function (id) {
            this.elements.index = id;
            this.popChannel = 'element';
            this.ga_btn('uslp_' + (id + 1))

        },
        lockScroll: function () {

            var html = $('body');
            this.htmlScollPosition = self.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
            html.css('overflow', 'hidden');
            window.scrollTo(0, this.htmlScollPosition);
            // console.log(this.htmlScollPosition);
            $(document.body).on("touchmove", this.preventScroll);
        },
        unLockScroll: function () {
            var html = $('body');
            var scrollPosition = this.htmlScollPosition;

            if (scrollPosition) {
                html.css('overflow', 'auto');
                window.scrollTo(0, scrollPosition);
                $(document.body).off("touchmove", this.preventScroll);
                scrollPosition = 0;
            }
        },
        preventScroll: function (e) {
            e.preventDefault();
            e.stopPropagation();
        },
        playIntro: function () {
            var t = this;
            t.introAni.play();
            $(window).off('touchstart click DOMMouseScroll mousewheel', t.playIntro);
        },
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
        onlyNum: function (e) {
            e.target.value = e.target.value.replace(/[^0-9.]/g, '');
        },
        checkForm: function (e) {
            this.ga_btn('quiz_send')


            this.formErrors = [];

            if (!this.userData.name || !this.userData.phone || !this.userData.email || !this.userData.marriage || !this.userData.hasChild) {
                this.formErrors.push("資料要填完才能抽獎喔!");
            }

            if (!this.validEmail(this.userData.email)) {
                this.formErrors.push('信箱檢查一下喔.');
            }

            if (this.userData.phone.length < 6) {
                this.formErrors.push('電話檢查一下喔.');
            }

            if (this.userData.hasChild == 'true' && this.userData.child.length == 0) {
                this.formErrors.push('小孩多大了阿?.');
            }

            if (!this.userData.agreeRule) {
                this.formErrors.push('請同意活動辦法與蒐集個人資料聲明');
            }

            if (this.formErrors.length == 0) {
                alert('感謝您的參與!');
                this.sendData();
                this.popChannel = '';
                this.pageScrollAni('#infoContainer')
                return;
            }

            alert(this.formErrors[0]);
            e.preventDefault();
        },
        sendData: function () {
            // 抓頁面token
            this.userData.token = $('#token').val();
            // console.log 全部資料
            console.log(this.userData);
            // api url
            var apiUrl = './store.php';


            axios.post(apiUrl, this.userData)
                // 成功
                .then(function (response) {
                    console.log(response);
                })
                // 失敗
                .catch(function (error) {
                    console.log(error);
                });
        },
        validEmail: function (email) {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        },
        onRecipe: function (id) {
            this.recipeChannel = id;
            this.popChannel = "recipe";
            this.ga_btn('recipe_' + (id + 1))

        },
        initScrollEvent: function () {
            var t = this;
            $(document).bind('scroll', function () {
                t.windowScrollTop = $(document).scrollTop()
                // var backToTopButton = $('.goTop');
                // if ($(document).scrollTop() > 250) {
                //     backToTopButton.addClass('isVisible');
                //     this.isVisible = true;
                // } else {
                //     backToTopButton.removeClass('isVisible');
                //     this.isVisible = false;
                // }
            }.bind(this));
        }
    }
});