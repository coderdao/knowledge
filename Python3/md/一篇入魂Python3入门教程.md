## Python3 下载
> Python3 最新源码，二进制文档，新闻资讯等可以在 Python 的官网查看到：<br />
Python 官网：https://www.python.org/<br /><br />
你可以在以下链接中下载 Python 的文档，你可以下载 HTML、PDF 和 PostScript 等格式的文档。<br />
Python文档下载地址：https://www.python.org/doc/

## 第一个Python3.x程序
```python
# -*- coding: utf-8 -*-
print("Hello, World!");
```
执行 python3 hello.py

### 标识符
- 第一个字符必须是字母表中字母或下划线 _ 。
- 标识符的其他的部分由字母、数字和下划线组成。
- 标识符对大小写敏感。

### python保留字
保留字即关键字，我们不能把它们用作任何标识符名称。Python 的标准库提供了一个 keyword 模块，可以输出当前版本的所有关键字：
```shell
>>> import keyword
>>> keyword.kwlist
['False', 'None', 'True', 'and', 'as', 'assert', 'break', 'class', 'continue', 'def', 'del', 'elif', 'else', 'except', 'finally', 'for', 'from', 'global', 'if', 'import', 'in', 'is', 'lambda', 'nonlocal', 'not', 'or', 'pass', 'raise', 'return', 'try', 'while', 'with', 'yield']
```

### 注释
- Python中单行注释以 # 开头
- 多行注释可以用多个 # 号，还有 ''' 和 """

### 缩进
python最具特色的就是使用缩进来表示代码块，不需要使用大括号 {} 。
缩进的空格数是可变的，但是同一个代码块的语句必须包含相同的缩进空格数。实例如下：
```shell
实例(Python 3.0+)
if True:
    print ("True")
else:
    print ("False")
```
缩进数的空格数不一致，会导致运行错误`IndentationError: unindent does not match any outer indentation level`

### 多行语句
Python 通常是一行写完一条语句，但如果语句很长，我们可以使用反斜杠(\)来实现多行语句，例如：
```python
total = item_one + \
       item_two + \
       item_three
```

在 [], {}, 或 () 中的多行语句，不需要使用反斜杠(\)，例如：
```python
total = ['item_one', 'item_two', 'item_three',
       'item_four', 'item_five']
```

### 等待用户输入
执行下面的程序在按回车键后就会等待用户输入：
```python
实例(Python 3.0+)
#!/usr/bin/python3

input("\n\n按下 enter 键后退出。")
```
### 同一行显示多条语句
Python可以在同一行中使用多条语句，语句之间使用分号(;)分割，以下是一个简单的实例：
```python
实例(Python 3.0+)
#!/usr/bin/python3
 
import sys; x = 'runoob'; sys.stdout.write(x + '\n')
```

### Print 输出
print 默认输出是换行的，如果要实现不换行需要在变量末尾加上 end=""：
```python
实例(Python 3.0+)
#!/usr/bin/python3
 
x="a"
y="b"
# 换行输出
print( x )
print( y )
 
print('---------')
# 不换行输出
print( x, end=" " )
print( y, end=" " )
print()
```

### import 与 from...import
在 python 用 import 或者 from...import 来导入相应的模块。
- 将整个模块(somemodule)导入，格式为： import somemodule
- 从某个模块中导入某个函数,格式为： from somemodule import somefunction
- 从某个模块中导入多个函数,格式为： from somemodule import firstfunc, secondfunc, thirdfunc
- 将某个模块中的全部函数导入，格式为： from somemodule import *

导入 sys 模块
```python
import sys
print('================Python import mode==========================');
print ('命令行参数为:')
for i in sys.argv:
   print (i)
print ('\n python 路径为',sys.path)
```

## Python 类型

### 数字(Number)类型:
python中数字有四种类型：整数、布尔型、浮点数和复数。
- int (整数), 如 1, 只有一种整数类型 int，表示为长整型，没有 python2 中的 Long。
- bool (布尔), 如 True。
- float (浮点数), 如 1.23、3E-2
- complex (复数), 如 1 + 2j、 1.1 + 2.2j

### 字符串(String)
- 使用三引号('''或""")可以指定一个多行字符串。
- 转义符 '\'
- 反斜杠可以用来转义，使用r可以让反斜杠不发生转义。。 如 r"this is a line with \n" 则\n会显示，并不是换行。
- 字符串可以用 + 运算符连接在一起，用 * 运算符重复。
- 字符串有两种索引方式，从左往右以 0 开始，从右往左以 -1 开始。
- 字符串的截取的语法格式如下：变量[头下标:尾下标:步长]
```python
word = '字符串'
sentence = "这是一个句子。"
paragraph = """这是一个段落，
可以由多行组成"""
```
