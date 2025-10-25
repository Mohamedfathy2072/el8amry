  <?php
    $appName = config('app.app') === 'kalksat' ? 'Elghamry' : 'Driftech';
    $logo = config('app.app') === 'kalksat' ? 'logo-brand.webp' : 'draftech-logo.svg';
  ?>
<footer id="footer" class="footer">
  <div class="copyright">
    &copy; Copyright <strong><span>{{$appName}}</span></strong>. All Rights Reserved
  </div>
  <div class="credits">
    Designed by <a href="https://www.linkedin.com/in/solieman-snossy/"></a>
  </div>
</footer>
