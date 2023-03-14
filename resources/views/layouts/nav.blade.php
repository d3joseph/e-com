<style>
    nav {
        background-color: #333;
        color: #fff;
        height:60px;
    }
    
    nav ul {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        
    }
    
    nav li {
        margin-right: 20px;
        
    }
    
    nav a {
        color: #fff;
        text-decoration: none;
        font-weight: bold;
        font-size: 16px;
        transition: all 0.2s ease-in-out;
        font-size: 20px;
        margin-left:50px;
       
    }
    
    nav a:hover {
        color: #ffcc00;
    }
</style>



<nav>
    <ul>
        <li><a href="{{ route('products.index') }}">Products</a></li>
        <li><a href="{{ route('orders.index') }}">Orders</a></li>
    </ul>
</nav>
