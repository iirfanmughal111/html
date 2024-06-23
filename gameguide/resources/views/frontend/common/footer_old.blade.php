<div class="footer_wrapper">
  <div class="container">
    <div class="row">
      <div class="col-sm-4 col-xs-12">
        <div class="copyright social-icon">
          <ul>
            <li><a href="https://www.facebook.com/webethics" target="_blank"><i class="fa fa-facebook"></i></a></li>
            {{-- <li><a href="" target="_blank"><i class="fa fa-twitter"></i></a></li> --}}
            <li><a href="https://www.instagram.com/webethics/" target="_blank"><i class="fa fa-instagram"></i></a></li>
          </ul>
        </div>
      </div>
	  @php $activeFaq='';  $activeTerms='';  $activePrivacy=''; $activeContact=''; @endphp
	  @if(collect(request()->segments())->last()=='faq')   
	   @php $activeFaq='active' @endphp
	  @endif
	  
	  @if(collect(request()->segments())->last()=='terms')   
	   @php $activeTerms='active' @endphp
	  @endif 
	  
	  @if(collect(request()->segments())->last()=='privacy')   
	   @php $activePrivacy='active' @endphp
	  @endif 
	  
	  @if(collect(request()->segments())->last()=='contact-us')   
	   @php $activeContact='active' @endphp
	  @endif
      <!--div class="col-sm-4 col-xs-12">
        <div class="footer_nav">
          <ul>
            <li class="{{$activeFaq}}"><a href="{{url('faq')}}">FAQ</a></li>
            <li class="{{$activeTerms}}"><a href="{{url('terms')}}">Terms</a></li>
            <li class="{{$activePrivacy}}" ><a href="{{url('privacy')}}">Privacy</a></li>
            <li class="{{$activeContact}}"><a href="{{url('contact-us')}}">Contact</a></li>
          </ul>
        </div>
      </div--->
      <div class="col-sm-4 col-xs-12">
        <div class="copyright">
          <p>Copyright © 2020 WEBETHICS. All Right Reserve. </p>
        </div>
      </div>
    </div>
  </div>
</div>