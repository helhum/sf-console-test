## Demonstrate breaking behaviour of [#33897](https://github.com/symfony/symfony/pull/33897)

In general I agree with the changes made with the intention to allow
providing input to the application using STDIN.
There are however some edge cases demonstrated here that need some attention.

### Tests with current state (symfony/console 5.0.7/4.4.7)

#### Test 1
Forcing detached terminal (no tty) and not providing any input via STDIN
`echo '' | php console.php hidden:input`
#### Expectation
Error message saying that two required arguments are missing
Output: 
```
Not enough arguments (missing: "password, action").
```
#### Actual
Infinite loop.
This definitely needs a fix in `AskHidden` handling to avoid the infinite loop.
Additionally it would be helpful to inform users that action argument is missing as well.
```
 Password:
 > stty: stdin isn't a terminal
stty: stdin isn't a terminal


                                                                                
 [ERROR] Password must not be empty.                                            
                                                                                

 Password:
 > stty: stdin isn't a terminal
stty: stdin isn't a terminal


                                                                                
 [ERROR] Password must not be empty.                                            
                                                                                
...

```

#### Test 2
Forcing detached terminal (no tty) and not providing any input via STDIN,
but providing password argument
`echo '' | php console.php hidden:input 123456`
#### Expectation
Password argument is set to "123456" and error message that action argument is missing
Output: 
```
Not enough arguments (missing: "action").
```
#### Actual
Works in general, but would be helpful to inform users that action argument is missing
```
 What do you want to do:
 > 

                                                                                
 [ERROR] Action must not be empty.                                              
                                                                                

 What do you want to do:
 > 
            
  Aborted.  
            

hidden:input <password> <action>
```

#### Test 3
Forcing detached terminal (no tty) and providing password argument via STDIN
`echo '123456' | php console.php hidden:input`
#### Expectation
Password argument is set to "123456" and error message that action argument is missing
Output: 
```
Not enough arguments (missing: "action").
```
#### Actual
Works in general, but would be helpful to inform users that action argument is missing.
```
 Password:
 > stty: stdin isn't a terminal
stty: stdin isn't a terminal


 What do you want to do:
 > 
            
  Aborted.  
            

hidden:input <password> <action>
```

