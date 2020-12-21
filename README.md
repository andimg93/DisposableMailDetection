##  PHP - Disposable Email Addresses Detection

After I had to realize that there is no good, fast and well maintained package for the detection of disposable mail addresses - I have now created one myself. 

About **130k disposable mail addresses** are currently stored. Not a single address exists twice - **No duplicates**!
PHP related there is no better performing package for validation. This is based on a simple isset check for the relevant mail part, which would be much faster than an in_array one - Particularly with such a large lookup/array.
The advantage of isset compared to in_array is briefly explained as follows:
- It uses an O(1) hash search on the key whereas in_array must check every value until it finds a match
- Being an opcode, it has less overhead than calling the in_array built-in function

The advantages of this package are therefore obvious:
- Outstanding performance
- The package with presumably the most deposited disposable mail addresses
- No dependencies to various other packages

