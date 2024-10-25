<?php
require_once 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .about-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .about-header h1 {
            font-size: 2.5em;
            color: #333;
            margin-bottom: 20px;
        }

        .about-text {
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.6;
            color: #666;
            margin-bottom: 50px;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }

        .team-member {
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .team-member:hover {
            transform: translateY(-5px);
        }

        .member-image {
            width: 100%;
            height: 300px;
            overflow: hidden;
        }

        .member-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .member-info {
            padding: 20px;
            text-align: center;
        }

        .member-info h3 {
            color: #333;
            font-size: 1.2em;
            margin-bottom: 10px;
        }

        .member-info p {
            color: #666;
            margin-bottom: 15px;
        }

        .member-role {
            color: #888;
            font-style: italic;
        }

        @media (max-width: 768px) {
            .team-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 20px;
            }
            
            .member-image {
                height: 250px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="about-header">
            <h1>About Us</h1>
            <div class="about-text">
                <p>We are a team of developers who have complete expertise in software development, from front-end to back-end. Our focus is on creating responsive, user-friendly, and reliable web applications. With capabilities in interface design, system development, to front-end and back-end integration, we are ready to provide innovative and scalable digital solutions. We are dedicated to producing high-quality work, addressing your technology needs, and staying up-to-date with the latest developments in the world of app development. But of course nothing is perfect in this world just like our web which still has many shortcomings, we hope you can all enjoy our web.</p>
            </div>
        </div>

        <div class="team-grid">
            <?php
            $team_members = [
                [
                    "name" => "Razhib Fauzul Haq",
                    "role" => "Front-End Developer",
                    "description" => "I'm Razhib Fauzul Haq as Front-End Developer focused on creating responsive, user-friendly web interfaces using modern technologies.",
                    "image" => "../image/RZB.jpg"
                ],
                [
                    "name" => "Ryan Erlanda Steffen",
                    "role" => "Back-End & Front-End Developer",
                    "description" => "I'm Ryan Erlanda Steffen as versatile Back-End and Front-End Developer skilled in building robust, provide solution, inovation, scalable systems and creating user-friendly interfaces.",
                    "image" => "../image/RYN.jpg"
                ],
                [
                    "name" => "Ghiyats Nabil Rabbani",
                    "role" => "Back-End Developer",
                    "description" => "I'm Ghiyats Nabil Rabbani, a Back-End Developer specializing in building secure and efficient web applications, with a focus on database security and server architecture.",
                    "image" => "../image/NBL.jpg"
                ],
                [
                    "name" => "Muhammad Abidzar Prayitno",
                    "role" => "Back-End Developer",
                    "description" => "I'm Muhammad Abidzar Prayitno as Back-End Developer siap menjadi badutmu",
                    "image" => "../image/ABZ.jpg"
                ]
            ];

            foreach($team_members as $member) {
                echo "<div class='team-member'>
                    <div class='member-image'>
                        <img src='" . $member["image"] . "' alt='" . $member["name"] . "'>
                    </div>
                    <div class='member-info'>
                        <h3>" . $member["name"] . "</h3>
                        <p class='member-role'>" . $member["role"] . "</p>
                        <p>" . $member["description"] . "</p>
                    </div>
                </div>";
            }
            ?>
        </div>
    </div>
</body>
</html>
<?php require_once 'footer.php'; ?>