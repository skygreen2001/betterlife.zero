<?php

	function compress_start(){
		ob_start();
		ob_implicit_flush(0);
	}

	function compress_check(){
		$enc = $_SERVER["HTTP_ACCEPT_ENCODING"];
	    if (headers_sent() || connection_status()!=0 ||$GLOBALS["stream"]=="xml" || !$GLOBALS["compression"]){
	        return false;
	    }
		//check if server wel met gzip encoding is gecompileerd
		if (strpos($enc,'gzip') !== false) return "gzip";
	    return false;
	}

	function compress_out(){
	    $ENCODING = compress_check();
	    $level=3;
        $contents = ob_get_contents();
        ob_end_clean();
		$contents_old=$contents;

		//debug("Response: ".$contents);

        $length=strlen($contents);
        if($length==0)$length=1;

        if ($ENCODING){
	        if($length==0)$length=1;
	        $newlength=strlen(gzcompress($contents,$level));
	        $compression=round((1-$newlength/($length))*100);

			$size = strlen($contents);
	        $crc = crc32($contents);
	        $contents = gzcompress($contents,$level);
	        $contents = substr($contents, 0, strlen($contents) - 4);
			$size_compressed=strlen($contents);
	        header("Content-Encoding: $ENCODING");
	        //header("Content-length: $size_compressed");
	        print "\x1f\x8b\x08\x00\x00\x00\x00\x00";
	        print $contents;
	        print pack('V',$crc);
	        print pack('V',$size);
	       	debug("Responsetime: ".$GLOBALS["timer"]->stop());
			//debug("-----------\r\n".$contents_old."\r\n-------------------------\r\n");
	        die() ;
	    }else{
			echo $contents;
			exit;
	    }
	}

?>