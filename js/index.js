Vue.use(VueAwesomeSwiper)






const router = new VueRouter({
    routes: [{
        path: '/rule',
        component: template_rule
    }, {
        path: '/product/:proid?',
        component: template_product
    }, {
        path: '/',
        component: template_index
    }],

})




var app = new Vue({
    router: router,
    data: {
        pageChannel: 'mainPage',
        popChannel: '',
        introAnimation: true,
        recipeChannel: 0,
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
        qa: vueDataQA,
        animal: vueDataAnimal,
        plant: vueDataPlants,
        elements: vueDataElements,
        products: vueDataProducts,
        showTopBtn: false,
        windowScrollTop: 0,
    },
    mounted: function () {
        var t = this;
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

            t.gaTimeout = setTimeout(function () {
                t.GAPage = $(e.target).attr('data-ga');
            }, 10);
        });

        // console.log(this.getUrlParameter("page"));
        // if (this.getUrlParameter("page") == 'rule') {
        //     console.log(this.introAni);
        //     if (this.introAni) {
        //         this.introAni.progress(1, false);
        //     }
        //     this.pageChannel = 'rule';
        // }

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
            console.log('GA_PAGE: ', inv);
            ga('send', 'pageview', inv);
        },
        ga_btn: function (inv) {
            console.log('GA_BUTTON: ', inv);
            ga('send', 'event', 'btn', inv);
        },
        plantsPopupSeeMore: function (id) {
            // this.pageChannel = "product";
            // this.popChannel = "";
            // this.pageScrollAni("#product_Swiper");
            // this.product_Swiper.slideTo(this.plant.data[id].seeMoreProductId);
            // this.ga_btn('index_' + this.plant.data[id].enName + '_more')
        },
        isPhone: function () {
            testExp = new RegExp('Android|webOS|iPhone|iPad|' +
                'BlackBerry|Windows Phone|' +
                'Opera Mini|IEMobile|Mobile',
                'i');
            return (testExp.test(navigator.userAgent))
        },
        goProduct: function () {
            this.popChannel = '';
        },
        onMenu: function (goto) {
            this.menuOpen = false;
            switch (goto) {
                case 'logo':
                    this.pageScrollAni('#index')
                    break;
                case 'map':
                    this.pageScrollAni('#intro')
                    break;
                case 'qa':
                    this.reQA();
                    break;
                case 'plant':
                    this.pageScrollAni('#infoContainer')
                    break;
                case 'product':
                    this.pageScrollAni('#productTitle')
                    this.ga_page('P4_product')
                    break;
                case 'rule':
                    this.pageScrollAni('#ruleTitle')
                    this.ga_page('P5_rule')
                    break;
                case 'winner':
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
                    scrollTop: target.offset().top - 55
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

        preventScroll: function (e) {
            e.preventDefault();
            e.stopPropagation();
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
            // var apiUrl = './store.php';


            // axios.post(apiUrl, this.userData)
            //     // 成功
            //     .then(function (response) {
            //         console.log(response);
            //     })
            //     // 失敗
            //     .catch(function (error) {
            //         console.log(error);
            //     });


            axios({
                method: 'post',
                url: './store.php',
                // 利用 transformRequest 进行转换配置
                transformRequest: [
                    function (oldData) {
                        // console.log(oldData)
                        let newStr = ''
                        for (let item in oldData) {
                            newStr += encodeURIComponent(item) + '=' + encodeURIComponent(oldData[item]) + '&'
                        }
                        newStr = newStr.slice(0, -1)
                        return newStr
                    }
                ],
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data: this.userData,
            })
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
        initScrollEvent: function () {
            var t = this;
            $(document).bind('scroll', function () {
                t.windowScrollTop = $(document).scrollTop()
            }.bind(this));
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
        onRecipe: function (id) {
            console.log(id);
            this.recipeChannel = id;
            this.popChannel = "recipe";
            this.ga_btn('recipe_' + (id + 1))
        },
        lockScroll: function () {
            var html = $('body');
            this.htmlScollPosition = self.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
            html.css('overflow', 'hidden');
            // console.log('lockScroll', this.htmlScollPosition);
            window.scrollTo(0, this.htmlScollPosition);
            $(document.body).on("touchmove", this.preventScroll);
        },
        unLockScroll: function () {
            var html = $('body');
            var scrollPosition = this.htmlScollPosition;
            scrollPosition = (scrollPosition == 0) ? 1 : scrollPosition;
            if (scrollPosition) {
                html.css('overflow', 'auto');
                window.scrollTo(0, scrollPosition);
                $(document.body).off("touchmove", this.preventScroll);
                scrollPosition = 0;
            }
        },
    }
}).$mount('#app');