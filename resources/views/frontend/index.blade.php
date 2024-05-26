
@include('frontend.element.head')
<body>

<div class="super_container">

	<!-- Header -->
	@include('frontend.element.header')

	@yield('content')

	<!-- Footer -->
	@include('frontend.element.footer')


</div>
@include('frontend.element.script')
