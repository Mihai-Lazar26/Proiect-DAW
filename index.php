<?php
    session_start();
    if(isset($_GET['error'])){
        $error = $_GET['error'];

        if($error = 'stmtfailed'){
            echo "<script type='text/javascript'>alert('Ceva nu a mers bine');</script>";
        }
        if($error = 'biletexists'){
            echo "<script type='text/javascript'>alert('Din pacate biletul nu mai este valabil');</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang = "ro">
    <head>
        <meta charset = "utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel = "stylesheet" href = "CSS/stil.css" type = "text/css">
        <title>Index</title>
    </head>
    <body>

    <?php
        include_once 'navbar.php';
    ?>

    <section class="p-5 container-text-lm">
            <div class="container text">
                <article class="blog-post p-5">
                    <h2 class="blog-post-title text-center"> Proiect DAW, Rezervare de bilete online film, Lazăr Mihai, grupa 234</h2>
                    <hr>
                    <h3 class="text-center">Descriere:</h3>
                    <p>Acest domeniu este o aplicație web pentru rezervarea biletelor online de film.</p>
                    <p>O persoană își poate crea un cont pe site prin intermediul unei adrese de mail.</p>
                    <p>Utilizatorul trebuie să își confirme adresa de mail într-un interval de 24 de ore.</p>
                    <h5>Tipuri de utilizatori:</h5>
                    <ul>
                        <li>guests care vor avea dreptul de a căuta filme si detalii despre difuzări dar nu vor putea face rezervări</li>
                        <li>utilizatori normali vor avea dreptul de a căuta filme, detalii despre difuzări și vor putea face rezervări</li>
                        <li>utilizatori manager cinematograf care vor avea dreptul de a adăuga date despre difuzări pentru cinematograful lor</li>
                    </ul>
                    <p>Conturile neconfirmate vor fi șterse din baza de date.</p>
                    <p>Informațiile legate de utilizatori vor fi stocate în mod securizat.</p>
                        <h5>Site-ul stochează în baza de date informații precum:</h5>
                        <ul>
                            <li>detalii despre filme cum ar fi titlul, regizorul, data lansări, durata si genurile filmului</li>
                            <li>detalii despre difuzări cum ar fi filmul, cinemaul și sala</li>
                            <li>detalii despre utilizatori</li>
                            <li>detalii despre cinemauri cum ar fi numele, adresa, sălile și locurile din săli</li>
                            <li>detalii despre biletele care au fost rezervate cum ar fi clientul care a făcut rezervarea și tipul biletului rezervat</li>
                        </ul>
                        
                        <p>Un client va rezerva bilete la un cinematograf, va plăti biletele la cinematograful ales, cinematograful va difuza filmul iar clientul îl va viziona.</p>
                    <hr>
                    <h3 class="text-center">Diagrama Conceptuală</h3>
                    <img src="Imagini/Diagrama Conceptuala.png" alt="" class="img-fluid mx-auto d-block">
                    <h3 class="text-center">Diagrama Use Case</h3>
                    <img src="Imagini/Diagrama Use Case.png" alt="" class="img-fluid mx-auto d-block">
                </article>
            </div>
        </section>



    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
    </body>
</html>