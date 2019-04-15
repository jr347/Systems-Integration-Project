echo -n "Enter mysql username: "
read username

echo -n "Enter name of database: "
read name

TIME=`date +%b-%d-%y`
FILE_NAME="$name"-"$TIME".sql
echo "$FILE_NAME"
mysqldump -u "$username" -p "$name" > "$FILE_NAME"
mysql -u "$username" -p test < "$FILE_NAME"
