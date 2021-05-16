What's a csv pretty printer?
In short, a csv pretty printer will read a csv file as input and print it like a table for output:

Input
post_id,phone,title,description,device_id
1,09122965123,oven,clean and perfect,3d345kfx
2,09382461163,fridge,beautiful & spacious & assured,xcvd932f
3,09322935123,desk,just a wooden desk,i3xd3z3o

Output
+-------------------------------------------------------------------+
|post_id|phone      |title |description                   |device_id|
+-------------------------------------------------------------------+
|1      |09122965123|oven  |clean and perfect             |3d345kfx |
+-------------------------------------------------------------------+
|2      |09382461163|fridge|beautiful & spacious & assured|xcvd932f |
+-------------------------------------------------------------------+
|3      |09322935123|desk  |just a wooden desk            |i3xd3z3o |
+-------------------------------------------------------------------+

Part I: Prologue
Implement a csv pretty printer for our clients! Your program should take one argument as input which is the csv file path and output to stdout the pretty printed version. For example:
./csv-pprint.out example.csv

Constraints

Each line has a fixed number of commas.
Each line is less than 80 characters.

Part II: Long Columns!
Our clients have reported that they need to use our program for files with longer lines. But they don't want to scroll horizontally in order to see the full content of a line. Therefore, they want to specify the max width of 80 for each column. Here is an example:

Input
post_id,phone,title,description,device_id
1,09122965123,oven,clean and perfect oven for baking cakes & making pizza & boiling canned food. Warning: ovens are not for programmers,3d345kfx
2,09382461163,fridge,beautiful & spacious & assured this is the Emersan fridge. for more information please call 911,xcvd932f
3,09322935123,desk,just a wooden desk,i3xd3z3o

Output
+---------------------------------------------------------------------------------------------------------------------+
|post_id|phone      |title |description                                                                     |device_id|
+---------------------------------------------------------------------------------------------------------------------+
|1      |09122965123|oven  |clean and perfect oven for baking cakes & making pizza & boiling canned food.   |3d345kfx |
|       |           |      |Warning: ovens are not for programmer                                           |         |
+---------------------------------------------------------------------------------------------------------------------+
|2      |09382461163|fridge|beautiful & spacious & assured this is the Emersan fridge. for more information |xcvd932f |
|       |           |      |please call 911                                                                 |         |
+---------------------------------------------------------------------------------------------------------------------+
|3      |09322935123|desk  |just a wooden desk                                                              |i3xd3z3o |
+---------------------------------------------------------------------------------------------------------------------+