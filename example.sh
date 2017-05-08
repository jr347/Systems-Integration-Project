input="/home/johnny/test/config"
cd /tmp/
mkdir text_folder
while IFS= read -r a
do cp $a /tmp/text_folder
done < "$input"
tar -czvf text_folder.tar.gz text_folder
cd /home/johnny/test

scp `./deploy.php`

cd /tmp/
rm -r text_folder
rm text_folder.tar.gz

./sendDeployR.php
