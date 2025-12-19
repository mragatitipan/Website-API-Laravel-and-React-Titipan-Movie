 <!-- Logout Modal-->
 <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content fs-normal">
      <div class="modal-header mx-3">
        <h5 class="modal-title fs-medium" id="logoutModalLabel">Ready to Leave?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body mx-3">Apakah anda yakin akan logout?</div>
      <div class="modal-footer mx-3">
        <button class="btn btn-secondary fs-normal" type="button" data-dismiss="modal">Cancel</button>
        {{-- <a class="btn btn-primary" href="{{ url('/login') }}">Logout</a> --}}
        <a class="btn btn-primary fs-normal" href="{{ route('logout') }}"
            onclick="event.preventDefault();
                          document.getElementById('logout-form').submit();">
            {{ __('Logout') }}
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
      </div>
    </div>
  </div>
</div>