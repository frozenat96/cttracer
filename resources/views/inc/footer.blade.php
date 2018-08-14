
<footer>
        <nav class="navbar navbar-expand-lg navbar-light nvfooter" style="width: 100%;background-color: #222;">
          <div class="collapse navbar-collapse">
            <ul class="navbar-nav  mr-auto" id="nvf1">
              <li class="nav-item">
                <a class="nav-link" data-toggle="modal" data-target="#about_modal" href="#">About CT-Tracer</a>
              </li>
              @if(Auth::user())
              <li class="nav-item">
                <a class="nav-link" href="/terms">Terms and Conditions</a>
              </li>
              @else
                <li class="nav-item">
                  <a class="nav-link" href="#" data-toggle="modal" data-target="#terms_modal">Terms and Conditions</a>
                </li>
              @endif
              <!--
              <li class="nav-item">
                <a class="nav-link" data-toggle="modal" data-target="#disclaimer_modal" href="#">Disclaimer</a>
              </li>
              -->
              <li class="nav-item">
                <a class="nav-link" data-toggle="modal" data-target="#contact_modal" href="#">Contact</a>
              </li>
            </ul>
            
          </div>
          
        </nav>
        <div style="color: white;background-color: #222;font-size: 0.8em;">
            <center>&copy 2018 CT-Tracer</center><br>
        </div>
      </footer>
      
      <!-- About -->
      <div class="modal" id="about_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">About CT-Tracer</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body jsfy">
              <h5> About CT-Tracer </h5>
              <h6> VISION </h6>
              <p> A leading Christian institution committed to total human development for the well-being of society and environment. </p>
      
              <h6> MISSION </h6>
              <p>
              Infuse into the academic learning the Christian faith anchored on the gospel of Jesus Christ.Provide an environment where Christian fellowship and relationship can be nurtured and promoted.Provide opportunities for growth and excellence in every dimension of the University life in order to strengthen competence, character and faith.Instill in all members of the University community an enlightened social consciousness and a deep sense of justice and compassion.Promote unity among peoples and contribute to national development.
              </p>   
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
      
      
      <!-- Terms -->
      <div class="modal" id="terms_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Terms and Conditions</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                      @if(!Auth::user())
                        @include('inc.terms-content')
                      @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
      
      
      <!-- Disclaimer -->
      <div class="modal" id="disclaimer_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Disclaimer</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              ...
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Contact -->
      <div class="modal" id="contact_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Contact</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
             <div id="nvul2">
          
              <span style="color: black;"> Contact Us: <br>
                  <i class="fas fa-phone"></i> Phone: +035 4226002 loc 345<br>
                  <i class="far fa-envelope"></i> Email: ccs@su.edu.ph<br>
                  <i class="fas fa-map-marker"></i> Address: 1 Hibbard Ave, Dumaguete, 6200 Negros Oriental 
              </span>
      
              
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>