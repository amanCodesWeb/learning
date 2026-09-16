
<!-- 
Late static binding allows you to reference the called class (the class where the method is actually invoked), not the class where it's defined.
It uses static:: instead of self::

problem with 
self:: always refers to the class where the method is originally defined, even if it's inherited.

-->
<?

class A {
    public static function who() {
        echo __CLASS__;
    }

    public static function test() {
        //self::who(); // Always calls A::who
        static::who();
    }
}

class B extends A {
    public static function who() {
        echo __CLASS__;
    }
}

B::test(); // Output: A  ❌ (not B) in case of self, in static:: output is B

