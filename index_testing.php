<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Siiranmäki Cabins</title>
<style>
  body { margin:0; font-family:Arial,sans-serif; background:url("https://images.unsplash.com/photo-1511497584788-876760111969?q=80&w=1932&auto=format&fit=crop&ixlib=rb-4.1.0") no-repeat center center fixed; background-size:cover; color:#333; }
  header { text-align:center; background:rgba(46,83,57,0.9); color:#fff; padding:2rem 1rem; }
  main { padding:2rem; max-width:1000px; margin:auto; background:rgba(255,255,255,0.95); border-radius:12px; }
  .cabins { display:flex; gap:1.5rem; margin-bottom:2rem; }
  .cabin-card { background:#fff; padding:1rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.1); flex:1; text-align:center; cursor:pointer; transition:transform 0.2s; }
  .cabin-card:hover { transform:scale(1.03); }
  .cabin-card img { width:100%; border-radius:8px; margin-bottom:0.5rem; max-height:200px; object-fit:cover; }
  .reservation { background:#fff; padding:2rem; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.1); margin-bottom:2rem; }
  form { display:flex; flex-direction:column; gap:1rem; }
  label { font-weight:bold; }
  input, select, button { padding:0.6rem; border-radius:6px; border:1px solid #ccc; font-size:1rem; }
  button { background:#2e5339; color:white; cursor:pointer; transition:background 0.3s; }
  button:hover { background:#3c6b4b; }
  #confirmation { margin-top:1.5rem; padding:1rem; background:#e6f4ea; border:1px solid #a4d5b2; border-radius:8px; text-align:center; }
  table { width:100%; border-collapse:collapse; margin-top:1rem; }
  th, td { border:1px solid #ccc; padding:0.6rem; text-align:center; }
  th { background:#2e5339; color:white; }
  footer { text-align:center; padding:1.5rem; background:rgba(46,83,57,0.9); color:white; margin-top:2rem; }
</style>
</head>
<body>

<header>
  <h1>Welcome to Siiranmäki Cabins</h1>
  <p>Choose your cabin and reserve your stay</p>
</header>

<main>
  <!-- Cabin Cards -->
  <section class="cabins">
    <div class="cabin-card" data-id="1">
      <h2>Helmi 1</h2>
      <img src="https://media.houseandgarden.co.uk/photos/63a1a9b588e2d802928c6499/2:3/w_2000,h_3000,c_limit/MFOX7961.jpg" alt="Helmi 1">
      <p>Cozy cabin by Käränkävaara ridge. Sleeps 4.</p>
    </div>
    <div class="cabin-card" data-id="2">
      <h2>Helmi 2</h2>
      <img src="https://cf.bstatic.com/xdata/images/hotel/max1024x768/615536116.jpg?k=1105a0cd9fd25cd7ebe53a200226d44a240536de1369da10089033a61471f2b9&o=&hp=1" alt="Helmi 2">
      <p>Rustic cabin with sauna. Sleeps 6.</p>
    </div>
    <div class="cabin-card" data-id="3">
      <h2>Helmi 3</h2>
      <img src="https://images.fineartamerica.com/images/artworkimages/mediumlarge/1/cabin-at-the-lake-thomas-nay.jpg" alt="Helmi 3">
      <p>Modern cabin with fireplace. Sleeps 6.</p>
    </div>
  </section>

  <!-- Reservation Form -->
  <section class="reservation">
    <h2>Reserve Your Cabin</h2>
    <form id="reservationForm" action="reserve.php" method="POST">
      <label for="cabin">Select Cabin:</label>
      <select id="cabin" name="cabin_id" required>
        <option value="">--Choose a cabin--</option>
        <option value="1">Helmi 1</option>
        <option value="2">Helmi 2</option>
        <option value="3">Helmi 3</option>
      </select>

      <label for="name">Full Name:</label>
      <input type="text" id="name" name="name" required>

      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required>

      <label for="people">Number of people:</label>
      <input type="number" id="people" name="people" min="1" max="10" value="1" required>

      <label><input type="checkbox" name="cleaning" id="cleaning"> Include cleaning (€100)</label>
      <label><input type="checkbox" name="linen" id="linen"> Include linen (€25 per person)</label>

      <label for="checkin">Check-in Date:</label>
      <input type="date" id="checkin" name="checkin" required>

      <label for="checkout">Check-out Date:</label>
      <input type="date" id="checkout" name="checkout" required>

      <p>Total price: €<span id="totalPrice">0</span></p>

      <button type="submit">Reserve</button>
    </form>
    <div id="confirmation"></div>
  </section>

  <!-- All Reservations Table -->
  <section class="all-reservations">
    <h2>All Reservations</h2>
    <?php
    $conn = new mysqli("localhost","root","","cabin_booking");
    if ($conn->connect_error) {
      echo "<p>Database connection error: ".$conn->connect_error."</p>";
    } else {
      $sql = "SELECT r.reservation_id, c.name AS cabin_name, g.name AS guest_name, g.email AS guest_email, 
                     r.people, r.cleaning, r.linen, r.start_date, r.end_date, r.created_at
              FROM reservations r
              JOIN cabins c ON r.cabin_id=c.cabin_id
              LEFT JOIN guests g ON r.guest_id=g.guest_id
              ORDER BY r.created_at DESC";
      $result = $conn->query($sql);
      if ($result && $result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Cabin</th><th>Guest</th><th>Email</th><th>People</th><th>Cleaning</th><th>Linen</th><th>Check-in</th><th>Check-out</th><th>Created At</th></tr>";
        while($row = $result->fetch_assoc()){
          echo "<tr>
                  <td>{$row['reservation_id']}</td>
                  <td>".htmlspecialchars($row['cabin_name'])."</td>
                  <td>".htmlspecialchars($row['guest_name'])."</td>
                  <td>".htmlspecialchars($row['guest_email'])."</td>
                  <td>{$row['people']}</td>
                  <td>".($row['cleaning']?"Yes":"No")."</td>
                  <td>".($row['linen']?"Yes":"No")."</td>
                  <td>{$row['start_date']}</td>
                  <td>{$row['end_date']}</td>
                  <td>{$row['created_at']}</td>
                </tr>";
        }
        echo "</table>";
      } else {
        echo "<p>No reservations yet.</p>";
      }
      $conn->close();
    }
    ?>
  </section>
</main>

<footer>
  <p>© 2025 Siiranmäki Cabins. All rights reserved.</p>
</footer>

<script>
const form = document.getElementById('reservationForm');
const totalPriceEl = document.getElementById('totalPrice');

function getNights(checkin, checkout){
  if(!checkin || !checkout) return 0;
  const start = new Date(checkin);
  const end = new Date(checkout);
  return Math.max(0, (end - start)/(1000*60*60*24));
}

function calculatePrice(){
  const cabin = form.cabin_id.value;
  const nights = getNights(form.checkin.value, form.checkout.value);
  const people = parseInt(form.people.value) || 1;
  const cleaning = form.cleaning.checked;
  const linen = form.linen.checked;

  if(!cabin || nights<1){
    totalPriceEl.textContent = "0";
    return;
  }

  let perNight = 100; // base price; can extend for per-cabin logic
  let total = perNight * nights;
  if(cleaning) total += 100;
  if(linen) total += 25*people;

  totalPriceEl.textContent = total;
}

form.addEventListener('input', calculatePrice);
</script>

</body>
</html>
