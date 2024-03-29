<?php

require_once 'vendor/autoload.php';

if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
    $location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $location);
    exit;
}

session_start();

$sessionProvider = new EasyCSRF\NativeSessionProvider();
$easyCSRF = new EasyCSRF\EasyCSRF($sessionProvider);

$token = $easyCSRF->generate('knorr');
// print_r($_SESSION);
?>
<!doctype html>
<html lang="en">

<head>
    <title>是誰住在友善農場裡?</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width= 580,user-scalable=0">
    <!-- hiding the Browser’s User Interface -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <!-- meta start -->
    <meta name="title" content="是誰住在友善農場裡?" />
    <meta name="description" content="自從採用友善栽種後，這裡居然變成動物的夢想王國！快來一探究竟！參加問答，還能抽多樣好禮！" />
    <meta name="keywords" content="" />
    <meta name="copyright" content="" />
    <link rel="image_src" href="images/share.jpg" />
    <!-- iOS 6 -->
    <meta name="apple-mobile-web-app-title" content="">
    <!-- OG -->
    <meta property="og:title" content="是誰住在友善農場裡?" />
    <meta property="og:site_name" content="是誰住在友善農場裡？" />
    <meta property="og:description" content="自從採用友善栽種後，這裡居然變成動物的夢想王國！快來一探究竟！參加問答，還能抽多樣好禮！" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="images/share.jpg" />
    <meta property="og:url" content="" />
    <meta property="fb:app_id" content="" />
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico" />
    <!-- js start-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
    <!-- Include the Swiper library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.7/js/swiper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <script src="https://unpkg.com/vue-router/dist/vue-router.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <!-- Swiper JS Vue -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.7/css/swiper.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/vue-awesome-swiper@3.1.2/dist/vue-awesome-swiper.js"></script>
    <script type="text/javascript" src="js/lib/jquery.inview.min.js"></script>
    <script type="text/javascript" src="js/lib/TweenMax.min.js"></script>
    <!-- ga -->
    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-5875274-40', 'auto');
        ga('send', 'pageview');
    </script>
    <!-- css start-->
    <link rel="stylesheet" href="css/default.css?v=1">


</head>

<body>
    <div id="app" v-cloak>
        <!-- <div id="logo" @click='onMenu("logo")'></div> -->
        <router-link to="/" @click.native='onMenu("logo")' id="logo"></router-link>
        <div id="menuBtn" @click='menuOpen=true' class='phoneShow2'></div>
        <div id="mainContainer">
            <keep-alive>
                <router-view @open-recipe="onRecipe" @ga-btn='ga_btn' @ga-page='ga_page' @start-qa='reQA' @on-plant='openPlant' @on-animal='openAnimal' @on-element='openElement' :intro-animation='introAnimation'></router-view>
            </keep-alive>

            <div id="footer">
                <div class="txt ls-1">
                    ©2019 Unilever Group |
                    <a target='_blank' href="https://www.unileverprivacypolicy.com/traditional_chinese/policy.aspx">隱私保護政策</a> |
                    <a target='_blank' href="https://www.unilever.com.tw/contact/contact-form/">連絡我們</a>
                </div>
            </div>
        </div>

        <!-- popUp -->
        <!-- <transition name="popUp"> -->
        <div class="popUp" v-if='popChannel!=""'>
            <div class="innerPopup">
                <div class="bg" @click='onPopupBg'></div>

                <!-- QA pop -->
                <div class="qAWrap" v-if='popChannel=="qa"'>

                    <!-- result -->
                    <transition name='qa'>
                        <div class="result container" v-if='qa.state==="result"'>
                            <div class="cloz" @click='popChannel=""'></div>
                            <h1 class="ta_c gTxt f36 fwb ls-1">結果揭曉</h1>
                            <h2 class="ta_c darkredTxt">{{qa.result[userData.score].title}}
                            </h2>
                            <h3 class="mt_s" v-html="qa.result[userData.score].info"></h3>
                            <div class="btns mt_m">
                                <div class="gBtn mx_a" @click='retry' v-if='userData.score < 5'>再試一次</div>
                                <div class="gBtn mx_a" @click='fillForm'>填寫資料</div>
                            </div>
                        </div>
                    </transition>




                    <!-- question -->
                    <!-- <transition name='qa'> -->
                    <div class="question container" v-if='qa.state==="question"'>
                        <div class="cloz" @click='popChannel=""'></div>
                        <h1 class='ta_c gTxt miltonianTxt f66'>Q{{qa.index+1}}</h1>
                        <h3 class="gTxt" v-html="qa.question[qa.index]"></h3>
                        <div class="df jcc mt_m gTxt mx-a">
                            <div class="inputWrap dib">
                                <label v-for="(value,index) in qa.answerOption[qa.index]" :key='index' class='scaleBtn'>
                                    <input class="styled-checkbox" type="radio" :name="'q'+ qa.index" value="value1" @click='qa.userAnswerNow=index'>
                                    <div class="txt">{{ value }}</div>
                                </label>
                            </div>
                        </div>
                        <div class="mt_m">
                            <div class="gBtn mx_a" :class={disable:qa.userAnswerNow===-1} @click='onAnswer'>確認送出</div>
                        </div>
                        <h3 class="gTxt ta_c fwb">{{ qa.index+1 + " / 5"}}</h3>
                    </div>
                    <!-- </transition> -->

                    <!-- answer -->
                    <!-- <transition name='qa'> -->
                    <div class="answer container" v-if='qa.state==="answer"'>
                        
                        <div class="cloz" @click='popChannel=""'></div>
                        <h1 class="ta_c gTxt f36 fwb" v-if="qa.answer[qa.index]==qa.userAnswerNow">答對囉！水啦！</h1>
                        <h1 class="ta_c gTxt f36 fwb" v-if="qa.answer[qa.index]!=qa.userAnswerNow">歪腰~答錯囉！</h1>
                        <h2 class=" gTxt ta_c">阿康伯來解答</h2>
                        <h3 class="mt_s" v-html="qa.answerInfo[qa.index]"></h3>
                        <div class="pic mt_s" :class='[qa.answerAnimalClass[qa.index]]'>
                        </div>
                        <div class="gBtn mx_a mt_s" @click='nextQuestion' v-if='qa.index < 4'>下一題</div>
                        <div class="gBtn mx_a mt_s" @click='nextQuestion' v-else>看結果</div>
                        
                        <h3 class="gTxt ta_c fwb">{{ qa.index+1 + " / 5"}}</h3>
                    </div>
                    <!-- </transition> -->



                </div>



                <!-- plant pop -->
                <div class="plantWrap container" v-if='popChannel=="plant"'>
                    <div class="cloz" @click='popChannel=""'></div>
                    <h1 class='gTxt ta_c fwb'>{{plant.data[plant.index].name}}</h1>
                    <div class="picWrap mt_s">
                        <img :src="plant.data[plant.index].picUrl" alt="">
                    </div>
                    <h3 class='mt_m'>{{plant.data[plant.index].info}}</h3>
                    <div class="txt mt_m f16 italicTxt gTxt">
                        {{plant.data[plant.index].tagName}}
                    </div>
                    <hr class='mt_m'>
                    <h3 class=' ta_c fwb ls-1'>相關產品</h3>
                    <div class="proWrap mt_s">
                        <img :src="'images/'+item" alt="" v-for="(item,index) in plant.data[plant.index].productArr">
                    </div>
                    <!-- <div class="gBtn mt_m mx_a arrBtn" @click='plantsPopupSeeMore(plant.index)'>產品介紹</div> -->
                    <router-link :to="'product/'+plant.data[plant.index].seeMoreProductId" class="gBtn mt_m mx_a arrBtn" @click.native='goProduct'>產品介紹</router-link>
                </div>

                <!-- animal pop -->
                <div class="animalWrap container" v-if='popChannel=="animal"'>
                    <div class="cloz" @click='popChannel=""'></div>
                    <div class="pic" :class='animal.ani_data[animal.index].popUpClassName'>
                    </div>
                    <div class=" ta_c">
                        <h1 class='gTxt'>{{animal.ani_data[animal.index].name}}</h1>
                        <h3 class='gTxt' v-html='animal.ani_data[animal.index].info'></h3>
                    </div>
                    <div class="txt mt_m">
                        <h3>{{animal.ani_data[animal.index].info2}}</h3>
                    </div>
                    <div class="txt mt_m f16 italicTxt gTxt">
                        {{animal.ani_data[animal.index].tagName}}
                    </div>
                </div>

                <!-- element pop-->
                <div class="elementWrap container" v-if='popChannel=="element"'>
                    <div class="cloz" @click='popChannel=""'></div>
                    <div class="pic">
                        <img :src="'images/'+(elements.index+1)+'.png'" alt="">
                    </div>
                    <h1 class="gTxt ta_c fwb f36 ls-1 mt_s">{{elements.data[elements.index].title}}</h1>
                    <h3 class="h3 mt_m gTxt ta_c">{{elements.data[elements.index].info}}</h3>
                </div>

                <!-- form pop -->
                <transition name='qa'>
                    <div class="formPop f18 container" v-if='popChannel=="form"'>
                        <form>
                            <div class="cloz" @click='popChannel=""'></div>
                            <h1 class='ta_c ls-1 gTxt fwb f32'>資料要填寫正確<br>才能參加抽獎唷
                            </h1>
                            <div class="styled-inputWrap mt_s hide" >
                                <div class="txt">token：</div>
                                <input type="text" name='token' id='token' value="<?php echo $token;?>">
                            </div>
                            <div class="styled-inputWrap mt_s hide" >
                                <div class="txt">score：</div>
                                <input type="text" v-model="userData.score" name='score'>
                            </div>
                            <div class="styled-inputWrap mt_s">
                                <div class="txt">姓名：</div>
                                <input type="text" v-model="userData.name" name='name'>
                            </div>
                            <div class="styled-inputWrap mt_s">
                                <div class="txt">手機：</div>
                                <input type="text" v-model="userData.phone" @keyup='onlyNum' name='phone'>
                            </div>
                            <div class="styled-inputWrap mt_s">
                                <div class="txt">E-mail：</div>
                                <input type="text" v-model="userData.email" name="email">
                            </div>
                            <div class="checkBoxWrap df mt_m">
                                <div class="txt">婚姻：</div>
                                <div class="checkBoxs df">
                                    <label>
                                        <input class="styled-checkbox small" name="marriage" type="radio" v-model="userData.marriage" value='1'>
                                        <div class="txt">已婚</div>
                                    </label>
                                    <label>
                                        <input class="styled-checkbox small" name="marriage" type="radio" v-model="userData.marriage" value='0'>
                                        <div class="txt">未婚</div>
                                    </label>
                                </div>
                            </div>
                            <div class="checkBoxWrap df mt_s">
                                <div class="txt">小孩：</div>
                                <div class="checkBoxs df">
                                    <label>
                                        <input class="styled-checkbox small" name="hasChild" type="radio" v-model="userData.hasChild" value='1'>
                                        <div class="txt">有</div>
                                    </label>
                                    <label>
                                        <input class="styled-checkbox small" name="hasChild" type="radio" v-model="userData.hasChild" @click='userData.child=[]' value='0'>
                                        <div class="txt">沒有</div>
                                    </label>
                                </div>
                            </div>
                            <transition name="fadeInLeft" mode="">
                                <div class="checkBoxWrap df" v-if="userData.hasChild=='1'">
                                    <div class="txt"></div>
                                    <div class="checkBoxs df fww">
                                        <label>
                                            <input class="styled-checkbox small" name="child" type="checkbox" v-model="userData.child" value="0-3">
                                            <div class="txt">3歲以下</div>
                                        </label>
                                        <label>
                                            <input class="styled-checkbox small" name="child" type="checkbox" v-model="userData.child" value="4-12">
                                            <div class="txt">4-12歲</div>
                                        </label>
                                        <label>
                                            <input class="styled-checkbox small" name="child" type="checkbox" v-model="userData.child" value="13-18">
                                            <div class="txt">13-18歲</div>
                                        </label>
                                        <label>
                                            <input class="styled-checkbox small" name="child" type="checkbox" v-model="userData.child" value="18+">
                                            <div class="txt">18歲以上</div>
                                        </label>
                                    </div>
                                </div>
                            </transition>
                            <div class="mt_m">
                                <label>
                                    <input class="styled-checkbox small" name="remktg_consent" type="checkbox" v-model='userData.remktg_consent' :true-value="1" :false-value="0">
                                    <div class="txt f12">我同意接收聯合利華及其合作夥伴的市場推廣資訊。（<a href="./#/rule?page=detail" target="_blank" class='underline linkColor fwb'>相關細節</a>）</div>
                                </label>
                                <label>
                                    <input class="styled-checkbox small" type="checkbox" v-model='userData.optin_cmpgn' :true-value="1" :false-value="0">
                                    <div class="txt f12">我已經詳細閱讀<a href="https://campaign.knorr.com.tw/sustainable-farm/#/rule" target='_blank' class='underline linkColor fwb'>活動辦法</a>與<a href="./#/rule?page=personalData" target='_blank' class='underline linkColor fwb'>蒐集個人資料聲明</a>。</div>
                                </label>
                            </div>
                            <div type="submit" class="mx_a gBtn mt_s" @click='checkForm'>完成送出</div>
                            <!-- <input type="submit" value="完成送出" class="mx_a gBtn mt_s"> -->
                        </form>
                    </div>
                </transition>

                <!-- recipe pop -->
                <transition name='pop'>
                    <div class="container" v-if='popChannel=="recipe"'>
                        <div class="cloz" @click='popChannel=""'></div>
                        <h2 class="ls-1 ta_c fwb gTxt f32">{{ products.recipes[recipeChannel].name }}</h2>
                        <img :src="'images/products/f'+products.recipes[recipeChannel].className+'.png'" alt="" class="db mx_a mt_s">
                        <div class="mt_m">
                            <i class="icon_foods"></i>
                            <span class="f24 gTxt">材料</span>
                        </div>
                        <div class="f22 dgTxt mt_s">
                            {{ products.recipes[recipeChannel].foods }}
                        </div>
                        <div class="mt_b">
                            <i class="icon_recipe"></i>
                            <span class="f24 gTxt">作法</span>
                        </div>
                        <div class="f22 dgTxt mt_s">
                            <ul class="numList">
                                <li v-for='step in  products.recipes[recipeChannel].recipe'>{{step}}</li>
                            </ul>
                        </div>
                        <div class="mt_m mx_a gBtn" @click='nextRecipe'>下一道</div>
                    </div>

                </transition>
            </div>
        </div>
        <!-- </transition> -->

        <!-- menu -->
        <div id="menu" :class='{phoneHide2:!menuOpen}'>
            <div class="cloz" @click='menuOpen=false'></div>
            <div class="inner f32  fwb gTxt">
                <!-- <router-link to="/rule" class="mBtn scaleBtn gaBtn">rule</router-link>
                <router-link to="/product" class="mBtn scaleBtn gaBtn">product</router-link> -->



                <router-link to="/" class="mBtn scaleBtn gaBtn" data-ga='menu_index' @click.native='onMenu("map")'>一起逛農場</router-link>
                <router-link to="/" class="mBtn scaleBtn gaBtn" data-ga='menu_quiz' @click.native='onMenu("qa")'>阿康伯出考題</router-link>
                <router-link to="/" class="mBtn scaleBtn gaBtn" data-ga='menu_info' @click.native='onMenu("plant")'>友善農場的秘密</router-link>
                <router-link to="/product" class="mBtn scaleBtn gaBtn" data-ga='menu_product' @click.native='onMenu("product")'>美味販賣部</router-link>
                <router-link to="/rule" class="mBtn scaleBtn gaBtn" data-ga='menu_rule' @click.native='onMenu("rule")'>活動辦法</router-link>
                <!-- <router-link to="/rule" class="mBtn scaleBtn gaBtn no-bd" data-ga='menu_award' @click.native='onMenu("winner")'>中獎名單</router-link> -->
                <div class="socialWrap">
                    <a target='_blank' href="https://www.knorr.com/tw/home.html" class="scaleBtn gaBtn" data-ga='menu_knorr'>
                        <div class="websiteBtn pic"></div>
                        <div class="txt gTxt f18">康寶官網</div>
                    </a>
                    <a target='_blank' href="https://www.facebook.com/knorrNO.1/" class="scaleBtn gaBtn" data-ga='menu_fb'>
                        <div class="fbBtn pic"></div>
                        <div class="txt gTxt f18">粉絲團</div>
                    </a>
                </div>
            </div>
        </div>

        <div id="topBtn" @click='goTop' :class='{show:showTopBtn}'></div>
    </div>


    <!-- 首頁 -->
    <script type="text/x-template" id="template_index">
        <div id="mainPage">
        <!-- <div id="mainPage" :class="{hide:pageChannel!='mainPage'}" > -->
            <div id="intro" class="gaPage" data-ga='P0_intro'>
                <div class="txtWrap fs">
                    <div class="txt">我阿康伯的農場</div>
                    <div class="txt">自從採用了友善地球的種田方法</div>
                    <div class="txt">變成了動物們的夢想王國</div>
                    <div class="txt">到底是誰住在這裡</div>
                    <div class="txt">趕嘛趕不走？</div>
                    <div class="txt">快跟著我去看麥咧！</div>
                    <div class="downArr txt"></div>
                    <div class="gTxt ta_c mt_s f16 ls-1 fwb">看更多</div>
                </div>
                <div class="clouds">
                    <div class="c1"></div>
                    <div class="c3"></div>
                    <div class="c2"></div>
                </div>
            </div>
            <div id="index" class='gaPage' data-ga='P1_index'>
                <div class="titleWrap">
                    <div class="title">
                        <div class="t1"></div>
                        <div class="t2"></div>
                        <div class="t3"></div>
                        <div class="t4"></div>
                        <div class="t5"></div>
                        <div class="t6"></div>
                        <div class="t7"></div>
                        <div class="t8"></div>
                        <div class="t9"></div>
                        <div class="t10"></div>
                        <div class="t11"></div>
                    </div>
                    <div class="subTitle ta_c fs aniEl">
                        動動手指按一按 <br>
                        讓阿康伯帶你一探究竟！
                    </div>
                    <div class="downArr aniEl"></div>
                    <div class="txt gTxt ta_c f16 mt_s ls-1 fwb aniEl">看更多</div>
                    <div class="birds">
                        <div class="eagle e1"></div>
                        <div class="eagle e2"></div>
                    </div>
                </div>
                <div class="clouds">
                    <div class="c1"></div>
                    <div class="c2"></div>
                    <div class="c3"></div>
                </div>
            </div>
            <div id="fieldContainer">
                <div class="field">
                    <div class="man1"></div>
                    <div class="man2"></div>
                    <div class="man3"></div>
                    <div class="man4"></div>
                    <div class="butterfly"></div>
                    <div class="butterfly bf2"></div>
                    <div class="butterfly bf3"></div>
                    <div class="butterfly bf4 phoneShow"></div>
                    <div class="butterfly bf5 phoneShow leftB"></div>
                    <div class="butterfly bf6 phoneShow"></div>
            
            
            
                    <div class="tagBtn" @click='$emit("on-animal",index)' @mouseover='stopDemo' @mouseleave='startDemo' v-for="(item,index) in animal.ani_data" :class='[item.className]'>
                        <div class="tag">{{item.name}}</div>
                    </div>
            
                    <div class="tagBtn" @click='$emit("on-plant",index)' @mouseover='stopDemo' @mouseleave='startDemo' v-for="(item,index) in plant.data" :class='[item.className]'>
                        <div class="tag">{{item.name}}</div>
                    </div>
            
                </div>
                <div class="subTitle ta_c ls-1">
                    <div class="animal">
                        <div class="eagle2"></div>
                        <div class="man2"></div>
                        <div class="bird"></div>
                    </div>
            
                    <h2>阿康伯出考題</h2>
                    <h1 class="ls-1 fwb">答對抽好禮</h1>
                    <h3 class='mt_s ls-1'>農場介紹有沒有仔細看？<br>
                        5題測驗考考你！<br>
                        <br>
                        ·la-boos便當盒餐具組<br>
                        ·康寶濃湯玉米系列(三袋)<br>
                        ·HOLA竹砧板(三入組)<br>
                        等你來抽！
                    </h3>
                    <div class="gBtn mx_a mt_m aniBtn gaBtn" data-ga='quiz_start' @click='$emit("start-qa")'>開始作答</div>
                </div>
            </div>

            <div id="infoContainer">
                <div class="innerContainer">
                    <div class="videoWrap gaPage" data-ga='P3_info'>
                        <h1 class='ta_c fwb  ls-1'>友善農場的秘密</h1>
                        <h3 class="ta_c mt_s">聽說阿康伯跟著康寶的規範<br>
                            一步一步經營<br>
                            才讓這裡超受動物歡迎！<br>
                            什麼規範這麼厲害？趕緊看下去！
                        </h3>
                        <div class="video mt_b">
                            <iframe allowfullscreen="" frameborder="0" height="100%" width="100%" src="//www.youtube.com/embed/M3pgWsxrP5A?rel=0&autoplay=0"></iframe>
                        </div>
                        <h2 class='ta_c fwb  ls-1 mt_s'>農場直擊影片</h2>
                        <!-- <div class="numBtns mt_b">
                            <div class="btn active">1</div>
                            <div class="btn">2</div>
                            <div class="btn">3</div>
                            <div class="btn">4</div>
                            <div class="btn">5</div>
                        </div> -->
                    </div>
                    <div class="foodWrap mt_bb gaPage" data-ga='P3_USLP'>
                        <h1 class="ta_c fwb ">康寶認為<br>真正營養美味的食物：</h1>
                        <div class="foods mt_b">
                            <div class="food">
                                <div class="pic">
                                    <img src="images/f1.png" alt="">
                                </div>
                                <h2 class="txt">
                                    健康的種子
                                </h2>
                            </div>
                            <div class="food">
                                <div class="pic">
                                    <img src="images/f2.png" alt="">
                                </div>
                                <h2 class="txt">
                                    尊重大自然的<br>
                                    耕作方式
                                </h2>
                            </div>
                            <div class="food">
                                <div class="pic">
                                    <img src="images/f3.png" alt="">
                                </div>
                                <h2 class="txt">
                                    負責的農夫<br>
                                    細心呵護
                                </h2>
                            </div>
                        </div>
                        <div class="subtxt ">
                            <i class="icon"></i>
                            <h3>康寶在國際間推動
                                「友善地球栽種農業標準11項規範」
                                收穫美味的同時，也對地球盡一份心力！
                                相信在每道料理中，你也能感受它的不同。</h3>
            
                        </div>
                    </div>
                    <div class="productWrap mt_bb mx_a">
                        <!-- elments -->
                        <div class="gBBtn" v-for="(item,index) in elements.data" @click='$emit("on-element",index)' :key='index'>
                            <div class="txtWrap">
                                <div class="pic mx_a" :class=[item.className]></div>
                                <div class="txt">{{item.name}}</div>
                                <div class="glass mx_a"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg"></div>
            </div>

        </div>
    </script>

    <!-- 產品 -->
    <script type="text/x-template" id="template_product">
        <!-- <div id="product" :class="{hide:pageChannel!='product'}" class='hide'> -->
        <div id="product">
            <div class="container">

                <h1 class='ta_c ls-1 fwb' id='productTitle'>自然原點<br>也是美味的起點</h1>
                <div class="video mt_b">
                    <iframe allowfullscreen="" frameborder="0" height="100%" width="100%" src="//www.youtube.com/embed/-Hqro-UhJvE?rel=0&autoplay=0"></iframe>
                </div>
                <swiper ref="product_Swiper" id='product_Swiper' :options="product_SwiperOption" class='mt_b product_Swiper mx_a' @transition-end="playSwiperAni" @transition-start='onProBuyCloz'>
                    <swiper-slide v-for='data in products.products'>
                        <div class="pic">
                            <img :src="'images/products/p'+ data.className +'1.png'" alt="" class='sub1 sub'>
                            <img :src="'images/products/p'+ data.className +'2.png'" alt="" class='sub2 sub'>
                            <img :src="'images/products/p_'+ data.className +'.png'" alt="" class=''>
                            <img :src="'images/products/p'+ data.className +'3.png'" alt="" class='sub3 sub'>
                        </div>
                        <div class="txtWrap">
                            <div class="f30 ta_c gTxt ls-1 fwb pro_name">
                                {{ data.name }}
                                <i class="icon_knorr"></i>
                            </div>
                            <ul class='gTxt mx_a f16 mt_s mb_s'>
                                <li v-for='note in data.note'>{{note}}</li>
                            </ul>
                            <div class="mt_m f16" v-for='ps in data.ps'>* {{ ps }}</div>
                        </div>
                        <!-- <transition name="fadeInLeft" mode="out-in">
                            <div class="gBtn mx_a mt_s scaleBtn" v-if='!wannaBuy' @click='onProBuy' key='on'>我要購買</div>
                            <div class="shopBtns mt_m" v-else key='off'>
                                <div class="cloz scaleBtn" @click='wannaBuy=false'></div>
                                <a :href="data.links.pchome" class="pchome scaleBtn" target='_blank' @click='ga_btn("product_EC_pchome")'></a>
                                <a :href="data.links.yahoo" class="yahoo scaleBtn" target='_blank' @click='ga_btn("product_EC_yahoo")'></a>
                                <a :href="data.links.momo" class="momo scaleBtn" target='_blank' @click='ga_btn("product_EC_momo")'></a>
                            </div>
                        </transition> -->
                        <div class="gBtn mx_a mt_s scaleBtn buyBtn">我要購買</div>
                        <div class="shopBtns mt_m hide">
                            <div class="cloz scaleBtn"></div>
                            <a :href="data.links.pchome" class="pchome scaleBtn" target='_blank' @click='ga_btn("product_EC_pchome")'></a>
                            <a :href="data.links.yahoo" class="yahoo scaleBtn" target='_blank' @click='ga_btn("product_EC_yahoo")'></a>
                            <a :href="data.links.momo" class="momo scaleBtn" target='_blank' @click='ga_btn("product_EC_momo")'></a>
                        </div>
                    </swiper-slide>
                    <div class="swiper-pagination" slot="pagination"></div>
                    <div class="swiper-button-prev" slot="button-prev"></div>
                    <div class="swiper-button-next" slot="button-next"></div>
                </swiper>
                <div class="df jcsa fww productTable mx_a mt_m phoneHide">
                    <div class="pro scaleBtn" v-for='(data,index) in products.products' :key='index' @click='product_Swiper.slideTo(index+1);pageScrollAni("#product_Swiper")'>
                        <img :src="'images/products/pp_'+ data.className +'.png'" alt="">
                        <div class="f16 mt_s ta_c ">{{ data.name }}</div>
                    </div>
                </div>

                <h1 class="ta_c mt_b ls-1 fwb">時令美味</h1>
                <div class="df jcsa fww recipeTable mx_a mt_m ">
                    <!-- <div class="recipe scaleBtn" v-for='(data,index) in products.recipes' :key='index' @click='onRecipe(index)'> -->
                    <div class="recipe scaleBtn" v-for='(data,index) in products.recipes' :key='index' @click='$emit("open-recipe",index)'>
                        <div class="pic" :class='["recipe_"+(index+1),data.season]'></div>
                        <div class="f16 ta_c ">{{ data.name }}</div>
                    </div>


                </div>
                <div class="video mt_b">
                    <iframe allowfullscreen="" frameborder="0" height="100%" width="100%" src="//www.youtube.com/embed/M3pgWsxrP5A?rel=0&autoplay=0"></iframe>
                </div>
            </div>
        </div>


    </script>

    <!-- 活動辦法 -->
    <script type="text/x-template" id="template_rule">
        <div id="rule">
        <!-- <div id="rule" :class="{hide:pageChannel!='rule'}" class='hide'> -->
            <div class="container f18">
                <h1 class='ta_c ls-1 fwb' id='ruleTitle'>活動辦法</h1>

                <!-- 問答挑戰 -->
                <div class="gTxt f28 mt_b switcher" :class='{ open : ruleSwitch.s1 }' @click='ruleSwitch.s1=!ruleSwitch.s1'>問答挑戰</div>
                <transition name='fadeInLeft'>
                    <div class="subContainer" v-if='ruleSwitch.s1'>
                        <p>
                            <span class="fwb">活動日期:</span>
                            <br>2019/03/15(五)–2019/04/26(五)止
                        </p>
                        <p>
                            <span class="fwb">玩法：</span>
                        </p>
                        <ul class="numList">
                            <li>看完「是誰住在友善農場裡？」的網站相關內容。</li>
                            <li>並參加問答挑戰，完成挑戰並留下完整資料。</li>
                        </ul>
                        <p>
                            <span class="fwb">獎項:</span>
                        </p>
                        <div class="df fww jcsb priceWrap">
                            <div class="pro df mt_m">
                                <div class="picWrap">
                                    <img src="images/r_3.png" alt="" class="mx-a">
                                </div>
                                <div class="txt">
                                    <div class="fwb">
                                        全部答對大獎
                                    </div>
                                    <div class="mt_s">
                                        永續竹製餐廚用具：<br>
                                        HOLA生熟食竹砧板三入組(市價799，共三名)
                                    </div>
                                </div>
                            </div>
                            <div class="pro mt_m df">
                                <div class="picWrap">
                                    <img src="images/r_2.png" alt="" class="mx-a">
                                </div>
                                <div class="txt">
                                    <div class="fwb">
                                        參加就能抽
                                    </div>
                                    <div class="mt_s">
                                        永續竹製餐廚用具：<br>
                                        la-boos日式野餐雙層便當餐具組(市價999，共三名)
                                    </div>
                                </div>
                            </div>
                            <div class="pro df mt_m">
                                <div class="picWrap">
                                    <img src="images/r_1.png" alt="" class="mx-a">
                                </div>
                                <div class="txt">
                                    <div class="fwb">
                                        加碼週週抽獎
                                    </div>
                                    <div class="mt_s">
                                        康寶濃湯自然原味玉米系列三袋(市價178，共18份)
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="f14 mt_m"> *贈品以實體為準。</div>
                        <p>
                            <span class="fwb">抽獎辦法：</span>
                        </p>
                        <ul class="numList mt_s">
                            <li>「永續竹製便當餐具組 」、「永續竹製生熟食砧板三入組 」將於04/29中午12:00抽出各三名得獎者，中獎名單將於05/03中午12:00公布於活動網站。</li>
                            <li>「康寶濃湯自然原味玉米系列三袋」週週抽將於03/25、04/01、04/08、04/15、04/22、04/29 中午12:00各別抽出三名前一週的得獎者，中獎名單將於03/27、04/03、04/10、04/17中午12:00依序公布於活動網站。</li>
                        </ul>
                        <p>
                            獎品將於領獎截止後寄出，也請得獎者耐心等候。贈獎注意事項：未留下完整及正確之聯絡資料之得獎者，主辦單位有權取消獲獎資格且不另抽候補名單。

                        </p>
                    </div>
                </transition>

                <!-- 抽獎辦法 -->
                <!-- <div class="gTxt f28 mt_b switcher" :class='{ open : ruleSwitch.s2 }' @click='ruleSwitch.s2=!ruleSwitch.s2'>抽獎辦法</div>
                <transition name='fadeInLeft'>
                    <div class="subContainer" v-if='ruleSwitch.s2'>
                        <ul class="numList">
                            <li class='mt_m'>「永續竹製便當餐具組 」、「永續竹製生熟食砧板三入組 」將於04/29中午12:00抽出各三名得獎者，中獎名單將於05/03中午12:00公布於活動網站。</li>
                            <li class='mt_m'>康寶濃湯自然原味玉米系列二袋」週週抽將於03/25、04/01、04/08、04/15、
                                04/22、04/29 中午12:00各別抽出三名前一週的得獎者，中獎名單將於03/27、04/03、04/10、04/17中午12:00依序公布於活動網站。</li>
                            <li class='mt_m'>「康寶2人份熱賣口味濃湯組合一份」將於04/29中午12:00抽出各三名得獎者，中獎名單將於05/03中午12:00公布於活動網站。</li>
                        </ul>

                        <p>
                            獎品將於領獎截止後寄出，也請得獎者耐心等候。贈獎注意事項：未留下完整及正確之聯絡資料之得獎者，主辦單位有權取消獲獎資格且不另抽候補名單。
                        </p>
                    </div>
                </transition> -->

                <!-- 貼文留言活動 -->
                <div class="gTxt f28 mt_b switcher" :class='{ open : ruleSwitch.s3 }' @click='ruleSwitch.s3=!ruleSwitch.s3'>貼文留言活動</div>
                <transition name='fadeInLeft'>
                    <div class="subContainer" v-if='ruleSwitch.s3'>
                        <p><span class="fwb">活動日期:</span> <br>2019/04/19(五)–2019/04/26(五)止</p>

                        <p>
                            <span class="fwb">玩法：</span>
                            <br>參加「康寶 no.1料理點子王」粉絲團「是誰住在友善農場裡？」相關活動貼文，依貼文規定回答問題，將抽出六名答對者，贈送「康寶2人份熱賣口味濃湯組合一份」。
                        </p>
                        <p>
                            <span class="fwb">獎項:</span>
                        </p>
                        <div class="df fww jcsb priceWrap">
                            <div class="pro df mt_m">
                                <div class="picWrap">
                                    <img src="images/r_4.png" alt="" class="mx-a">
                                </div>
                                <div class="txt">
                                    <div class="fwb">
                                        參加就能抽
                                    </div>
                                    <div class="mt_s">
                                        康寶2人份熱賣口味濃湯組合一份(市價288)
                                    </div>

                                </div>
                            </div>
                        </div>
                        <p class="f14">
                            *贈品以實體為準。
                        </p>
                        <p>
                            <span class="fwb">抽獎辦法：</span>
                        </p>
                        <ul class="numList mt_s">
                            <li>「康寶2人份熱賣口味濃湯組合一份」將於04/29中午12:00抽出各三名得獎者，中獎名單將於05/03中午12:00公布於活動網站。</li>
                        </ul>
                    </div>
                </transition>

                <!-- 中獎名單 -->
                <!-- <div id='winner' class="gTxt f28 mt_b switcher" :class='{ open : ruleSwitch.s4 }' @click='ruleSwitch.s4=!ruleSwitch.s4'>中獎名單</div>
                <transition name='fadeInLeft'>
                    <div class="subContainer" v-if='ruleSwitch.s4'>
                        <p></p>
                        <p>問答挑戰-週週抽 <br>
                            第一週:陳Ｘ伊、林Ｘ子、許Ｘ柔 <br>
                            第二週:陳Ｘ伊、林Ｘ子、許Ｘ柔 <br>
                            第三週:陳Ｘ伊、林Ｘ子、許Ｘ柔 <br>
                            第四週:陳Ｘ伊、林Ｘ子、許Ｘ柔 <br>
                            第五週:陳Ｘ伊、林Ｘ子、許Ｘ柔 <br>
                            第六週:陳Ｘ伊、林Ｘ子、許Ｘ柔
                        </p>

                        <p>
                            問答挑戰-永續竹製餐具 <br>
                            陳Ｘ伊、林Ｘ子、許Ｘ柔
                        </p>

                        <p>
                            問答挑戰-全部答對獎 <br>
                            陳Ｘ伊、林Ｘ子、許Ｘ柔
                        </p>
                    </div>
                </transition> -->

                <!-- 活動注意事項 -->
                <div class="gTxt f28 mt_b switcher" :class='{ open : ruleSwitch.s5 }' @click='ruleSwitch.s5=!ruleSwitch.s5'>活動注意事項</div>
                <transition name='fadeInLeft'>
                    <div class="subContainer" v-if='ruleSwitch.s5'>
                        <ul class="numList">
                            <li class="mt_m">全部獎項抽出後於本網站公告得獎人名單，中獎人同意主辦單位及活動小組進行蒐集、電腦相關處理及利用，並授權公開公佈相關中獎人資料。</li>
                            <li class="mt_m">得獎名單將公布於Facebook與活動網站，不另行通知，得奬者需私訊「真實姓名、電話與地址」作為領獎資料，得獎者若未於公告時間內(05/10)私訊回覆到康寶粉絲團者，視同放棄資格。如因得獎者更改領獎資料未通知主辦單位，致未收到獎品，視同得獎者放棄得獎權利，主辦單位有權取消獲獎資格且不另抽候補名單。</li>
                            <li class="mt_m">本活動獎品寄送地區僅限臺灣地區(包含本島及金門、澎湖、馬祖等臺灣外島)，恕無法寄送至前述地區以外之海外地區。</li>
                            <li class="mt_m">完成問答挑戰者及全數答對者，同一筆名單限中獎1次，重覆中獎者，主辦單位得重新抽出遞補人選，活動加碼週週抽不在此限。</li>
                            <li class="mt_m">參加者於參加本活動之同時，即視為同意接受本活動之活動辦法、注意事項及特別聲明、聯合利華隱私保護原則等規範。如有違反前述任一內容之情事，主辦單位將取消其得獎資格。</li>
                            <li class="mt_m">如有任何因個人電腦、網路、電話、技術性或不可歸責於主辦單位之事由，而使參加者所寄出或登錄之資料有遲延、遺失、錯誤、無法辨識或毀損之情況致無法參加本活動，主辦單位不負任何法律責任，參加者亦不得因此異議。</li>
                            <li class="mt_m">一旦發現有蓄意攻擊本活動相關網站或本活動之程式、或有使用該等程式之參加者、或以任何方式擾亂本活動公平原則之行為(包括但不限於以任何方式導致系統異常或竄改網站程式機制而影響活動結果)，主辦單位有權取消該參加者之參加紀錄與抽獎資格。因此得獎者，其得獎無效，並應將其所得獎項返還予主辦單位。</li>
                            <li class="mt_m">參加者保證所有填寫、提出之個人資料均為真實且正確，如有不實或不正確之情事，將被取消得獎資格，且如有致生損害於主辦單位或其他任何第三人，應負一切民刑事責任。主辦單位對於任何不實或不正確之資料不負任何法律責任。</li>
                            <li class="mt_m">抽獎及得獎資格均須經主辦單位查驗合格方為有效，一切查驗標準以主辦單位為準，並為唯一憑據，拒絕配合之參加者即喪失該資格。</li>
                            <li class="mt_m">中獎者應同意，除以主辦單位自行進口販售的商品為獎品者外，主辦單位對於中獎者因使用本活動獎品而導致之任何事故不須負任何賠償責任。所有獎品日後之使用與維修，主辦單位概不負責。</li>
                            <li class="mt_m">贈品運送過程中，因非可歸責於主辦單位之事由造成損壞、延遲、錯遞或遺失，主辦單位概不負責。</li>
                            <li class="mt_m">所有獎品均不得兌換現金及不得轉讓。</li>
                            <li class="mt_m">主辦單位擁有保留、修改、暫停及解釋活動內容之權利，修改訊息將於活動網站上公佈，不另行通知。</li>


                        </ul>


                    </div>
                </transition>

                <!-- 蒐集個人資料聲明 -->
                <div id='personalData' class="gTxt f28 mt_b switcher" :class='{ open : ruleSwitch.s6 }' @click='ruleSwitch.s6=!ruleSwitch.s6'>蒐集個人資料聲明</div>
                <transition name='fadeInLeft'>
                    <div class="subContainer" v-if='ruleSwitch.s6'>
                        <p class='fwb'>我們向您蒐集的個人資料</p>
                        <ul class="numList">
                            <li class="mt_m"> 聯合利華股份有限公司(下稱聯合利華)在您自願情況下蒐集您的個人資料。</li>
                            <li class="mt_m"> 「個人資料」是指任何關於您的資料或訊息，使（a）從該資料;或（b）從我們具有或可能具有的資料和其他訊息，可確定您的身分。我們可以向您蒐集如下所列出的個人資料類別，包括 *電郵、*姓名、*聯絡電話和以任何形式所提供給我們或通過其他與您互通的訊息。</li>
                            <li class="mt_m"> 您可自願選擇是否向聯合利華提供您的個人資料，若拒絕提供上文第2段具有星號標記的個人資料，恕我們未必可以向您提供我們的產品或服務，因我們不能確認您的身分，若有不便之處敬請見諒。</li>
                        </ul>
                        <p class='fwb'>
                            我們如何利用您的個人資料
                        </p>
                        <ul class="numList">
                            <li class="mt_m">蒐集及利用個人資料目的包括： a. 通知您相關活動訊息； b. 內部市場及客戶之統計調查； c. 遵守任何法律，法規或法院命令規定需要揭露的責任；及 d.任何直接如上述目的有關的其他目的。</li>
                            <li class="mt_m">利用個人資料方式包括以自動化機器或其他非自動化之方式。</li>

                        </ul>

                        <p class='fwb'>我們將您的個人資料轉移给哪些第三方</p>
                        <ul class="numList">
                            <li class="mt_m">我們會向下列第三方揭露您的個人資料，用於以下目的：
                                <br>(A) 任何第三方服務供應商、管理、經營或執行您的帳號或全球會籍的商業合作夥伴或顧問及/或我們的一般業務管理；及
                                <br>(B) 按照上文第4（c）段任何法律、法規、政府、稅務、執法或其他主管機關所需要揭露的。</li>
                            <li class="mt_m">我們可能會轉移並儲存您的個人資料至臺灣以外的地方。我們會採取一切合理需要的措施確保您的個人資料安全和根據蒐集個人資料聲明及個人資料保護法處理。</li>


                        </ul>
                        <p class='fwb' id='detail'>直接促銷</p>
                        <ul class="numList">
                            <li class="mt_m">聯合利華希望可使用您的個人資料作直接促銷目的，但我們在未得到您的書面同意之前不能如此使用您的個人資料。如果您以勾選在我們活動頁面上的相關空格或使用其他書面方式表示您同意，我們可不時根據您在相關空格、或其他書面方式所同意的個人資料（例如，您的姓名、聯絡方式等），依下列目的使用您的個人資料：
                                <br>(A) 促銷我們的產品和服務; 和/或
                                <br>(B) 推廣我們的贈獎活動、會員計畫、品牌聯合或優惠計畫及相關服務和活動。</li>
                            <li class="mt_m">聯合利華亦希望可提供您的個人資料給第三者作直接促銷目的，但我們在未得到您的同意之前不能如此使用您的個人資料。如果您以勾選在我們活動頁面上的相關空格或使用其他書面方式表示您同意，我們可不時根據您在相關空格、或其他書面方式所同意的個人資料（例如，您的姓名、聯絡方式等），與下列各方分享您的個人資料：
                                <br>(A) 本集團內的任何單位：（i）促銷我們認為您可能感興趣的產品和服務；和/或（ii）推廣自己的贈獎活動、會員計畫、品牌聯合或優惠計畫及相關服務；和/或
                                <br>(B) 我們的品牌合作夥伴，讓他們促銷其服務和產品。</li>

                        </ul>
                        <p class='fwb'>安全保存</p>
                        <ul class="numList">
                            <li class="mt_m">我們將採取所有合理措施以確保您的個人資料安全。為了防止未經授權使用或揭露，我們已實施技術和組織安全措施，以防護和保護您的個人資料，並確保根據蒐集個人資料聲明和適用的法律使用個人資料。</li>
                            <li class="mt_m">個人資料在網際網路被儲存、使用或傳輸不是完全安全的。我們將採取一切合理措施保護您的個人資料，但我們沒有任何控制權，也不能保證您透過網際網路傳送的個人訊息的安全性。因此，任何此類儲存、使用或傳輸需您自擔風險。</li>
                            <li class="mt_m">我們的行動應用程式及網站可能、不時和第三方的網站連接。如果您連接到這些網站，請注意，這些網站可擁有自己的隱私政策，而我們亦不會為上述政策承擔任何責任或義務。請向這些網站提交任何個人資料前，查看政策。</li>
                            <li class="mt_m">我們將按照相關法律、監管規定和我們內部資料保留政策規定，在必要的時間内保留您的個人資料。</li>

                        </ul>
                        <p class='fwb'>Cookies </p>
                        <ul class="numList">
                            <li class="mt_m">有關我們如何使用cookies，請參考我們的Cookie Policy
                                <a href="https://www.unilevercookiepolicy.com/tc/policy.aspx" target="_blank" class='tdul'>(https://www.unilevercookiepolicy.com/tc/policy.aspx)</a>。
                            </li>
                        </ul>

                        <p class='fwb'>您的資料隱私權</p>
                        <ul class="numList">
                            <li class="mt_m">聯合利華將致力保護您提供之個人資料。依據個人資料保護法第三條規定，您了解，就您所提供之個人資料可以用後述任何方式聯絡我們 (電話號碼：0800-311-699，郵寄地址：11071台北市信義區忠孝東路四段550號3樓，電子信箱：cec.taiwan@unilever.com)，行使下列權利：
                                <br> (一) 得向聯合利華查詢或請求閱覽、請求製給複製本，惟聯合利華依法得酌收必要成本費用；
                                <br> (二) 得向聯合利華請求補充或更正，惟依法您應為適當之釋明；
                                <br> (三) 得向聯合利華請求停止蒐集、處理或利用以及請求刪除，惟依法聯合利華因執行職務所必須者，得不依您的請求為之。 </li>
                            <li class="mt_m">如需要更多關於我們如何處理您的個人資料，請參考我們的聯合利華隱私保護政策（<a href="https://www.unileverprivacypolicy.com/traditional_chinese/policy.aspx" target="_blank" class='tdul'>https://www.unileverprivacypolicy.com/traditional_chinese/policy.aspx.</a>）。</li>
                                
                        </ul>

                    </div>
                </transition>
            </div>
        </div>
    </script>

    <script src="js/vue-data-qa.js"></script>
    <script src="js/vue-data-elements.js"></script>
    <script src="js/vue-data-products.js"></script>

    <script src="js/template_product.js"></script>
    <script src="js/template_rule.js"></script>
    <script src="js/template_index.js"></script>

    <script type="text/javascript" src="js/index.js"></script>

</body>

</html>