(function($){
$.fn.extend({        
        Scroll:function(opt,callback){                
                //������ʼ��                
                if(!opt) var opt={};                
                var _BtnRight = $("#"+ opt.right);//Shawphy:���Ұ�ť                
                var _BtnLeft = $("#"+ opt.left);//Shawphy:����ť                
                var timerID;                
                var _this=this.eq(0).find("ul:first");                
                var     lineW=_this.find("li:first").width(), //��ȡ�б���
                        line=opt.line?parseInt(opt.line,10):parseInt(this.width()/lineW,10), //ÿ�ι�����������Ĭ��Ϊһ�������������߶�                                                
												speed=opt.speed?parseInt(opt.speed,10):500; //���ٶȣ���ֵԽ���ٶ�Խ�������룩                                                
												timer=opt.timer; //?parseInt(opt.timer,10):3000; //������ʱ���������룩                
								if(line==0) line=1;                
								var rightWidth=0-line*lineW;                
								//��������                
								var scrollRight=function(){                        
								        _BtnRight.unbind("click",scrollLeft); //Shawphy:ȡ�����Ұ�ť�ĺ�����                        
								        _this.animate({                                
								                marginLeft:rightWidth
												},speed,function(){                                
												        for(i=1;i<=line;i++){                                        
												                _this.find("li:first").appendTo(_this);                                
												        }                                
												        _this.css({marginLeft:0});                                
												        _BtnRight.bind("click",scrollLeft); //Shawphy:�����Ұ�ť�ĵ���¼�                        
												});                 
								}                
								//Shawphy:����ҳ����                
								var scrollLeft=function(){                        
												_BtnLeft.unbind("click",scrollRight);                        
												for(i=1;i<=line;i++){                                
												        _this.find("li:last").show().prependTo(_this);                        
												}                        
												        _this.css({marginLeft:rightWidth});                        
												        _this.animate({                                
												marginLeft:0                        
												},speed,function(){                                
												        _BtnLeft.bind("click",scrollRight);                        
												});                
								}
								//Shawphy:�Զ�����                
								var autoPlay = function(){
												if(timer)timerID = window.setInterval(scrollRight,timer);                
								};                
								var autoStop = function(){                        
												if(timer)window.clearInterval(timerID);                
								};                 
								//����¼���                
								_this.hover(autoStop,autoPlay); autoPlay();                
								_BtnRight.css("cursor","pointer").click( scrollLeft ).hover(autoStop,autoPlay);//Shawphy:������������¼���                
								_BtnLeft.css("cursor","pointer").click( scrollRight ).hover(autoStop,autoPlay);         
				}      
})
})(jQuery);
