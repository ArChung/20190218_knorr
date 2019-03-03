var vm_product = null;

var template_product = {
    template: '#template_product',
    data: function () {
        return {
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
                        if ($(e.target).hasClass('buyBtn')) {
                            vm_product.onProBuy();
                        }
                        if ($(e.target).hasClass('cloz')) {
                            vm_product.onProBuyCloz();
                        }
                    }
                }
            },
            products: vueDataProducts,
            popChannel: '',
            plant: vueDataPlants,
            
        }
    },
    created: function () {
        vm_product = this;
    },
    computed: {
        product_Swiper: function () {
            console.log(this.$refs.product_Swiper.swiper);
            return this.$refs.product_Swiper.swiper
        }
    },
    watch: {
        $route: function(){
            if(this.$route.params.proid){
                this.plantsPopupSeeMore(this.$route.params.proid);
            }
        }
    },
    mounted: function () {
        if(this.$route.params.proid){
            this.plantsPopupSeeMore(this.$route.params.proid);
        }
        


        $('.product_Swiper').on('inview', function (event, isInView) {
            setTimeout(function () {
                vm_product.playSwiperAni()
            }, 200);
        });
    },
    methods: {
        plantsPopupSeeMore: function (id) {
            this.pageScrollAni("#product_Swiper");
            this.product_Swiper.slideTo(+id+1);
            this.playSwiperAni();
        },
        ga_page: function (inv) {
            this.$emit('ga-page',inv)
        },
        ga_btn: function (inv) {
            this.$emit('ga-btn',inv)
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
        initSwiper: function () {
            // var swiper= this.product_Swiper.slides[0];
            var tl = new TimelineMax();
            tl.set($(this.product_Swiper.slides).find('.sub'), {
                autoAlpha: 0
            })
        },
        playSwiperAni: function () {
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
            $('#product_Swiper .shopBtns').removeClass('hide');
        },
        onProBuyCloz: function () {
            $('#product_Swiper .shopBtns').addClass('hide');
            $('#product_Swiper .buyBtn').removeClass('hide');
            
        },
    }
}