
<!--sidebar-menu-->
<div id="sidebar"><a href="{{url('/admin/dashboard')}}" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
 
  <ul>
    <li class="active"><a href="{{url('/admin/dashboard')}}"><i class="icon icon-home"></i> <span>Dashboard</span></a> </li>
     <li class="submenu"> <a href=""><i class="icon icon-file"></i> <span>Categories</span> <span class="label label-important">5</span></a>
      <ul>
        <li><a href="{{ url('/admin/add-category') }}">Add Category</a></li>
        <li><a href="{{ url('/admin/view-categories') }}">View Categories</a></li>
      </ul>
    </li>
     <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Product</span> <span class="label label-important">3</span></a>
      <ul>
        <li><a href="{{ url('/admin/add-product') }}">Add Product</a></li>
        <li><a href="{{ url('/admin/view-products') }}">View Products</a></li>
      </ul>
    </li>   
   
  </ul>
</div>
<!--sidebar-menu-->