1.为了解决TabPanel的tabWidth='auto'在IE6里的bug，修改了ExtJS库的原文件：
  ext-all.js
  原为：o.style.width=(l-(g-d))+"px"
  现为：if(this.tabWidth!=='auto'){o.style.width=(l-(g-d))+"px";}else{o.style.width ='auto';}
  ext-all-debug.js
  ext-all-debug-w-comments.js
  原为：inner.style.width = (each - (tw-iw)) + 'px';
  现为：if(this.tabWidth!=='auto'){inner.style.width = (each - (tw-iw)) + 'px';}else{inner.style.width ='auto';}

