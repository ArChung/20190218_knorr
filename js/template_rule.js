
var template_rule = {
    template: '#template_rule',
    data: function () {
        return {
            ruleSwitch: {
                s1: true, //問答挑戰
                s2: true, //抽獎辦法
                s3: true, //貼文留言活動
                s4: true, //中獎名單
                s5: false, //活動注意事項
                s6: false //蒐集個人資料聲明
            },
        }
    },
    mounted: function () {
        var t = this;
        switch(this.$route.query.page){
            case 'detail':
                t.ruleSwitch.s6 = true;
                t.pageScrollAni('#detail')
                break
        }
        
    },
    methods: {
        pageScrollAni: function (el) {
            var $body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');
            setTimeout(function () {
                var target = $(el);
                $body.animate({
                    scrollTop: target.offset().top - 55
                }, 600);
            }, 10)
        },
    }
}