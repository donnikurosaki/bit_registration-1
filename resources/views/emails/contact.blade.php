<!DOCTYPE html>
<html>
<head>
    <title>Nouveau message de contact</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            /* background-color: #f72585; */
            background-color: #C5075C;
            color: white;
            padding: 15px;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .logo {
            background-color: white;
            display: inline-block;
            padding: 15px;
            border-radius: 5px;
        }
        .logo img {
            max-height: 40px;
            width: auto;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-top: 30px;
        }
        .label {
            font-weight: bold;
            color: #4361ee;
        }
    </style>
</head>
<body>
    <div class="header">
        {{-- <img style="height: 80px; width: auto;" src="{{ url('images/logo-institut.png') }}" alt="Logo Institut"> --}}
        <div class="logo">
            <img style="height: 80px; width: auto;" src="http://bit.bf/wp-content/uploads/2018/10/logo-bit-3_student_png-signetbit.png" alt="Logo Institut">
        </div>
        <h1>Nouveau message du site BIT Admission</h1>
    </div>
    
    <div class="content">
        <p><span class="label">Nom:</span> {{ $data['name'] }}</p>
        <p><span class="label">Email:</span> {{ $data['email'] }}</p>
        <p><span class="label">Sujet:</span> {{ $data['subject'] }}</p>
        
        <p><span class="label">Message:</span></p>
        <p>{{ $data['message'] }}</p>
    </div>
    
    <div class="footer">
        <p>Ce message a été envoyé depuis le formulaire de contact du site BIT.</p>
        <p>© {{ date('Y') }} Burkina Institute of Technology. Tous droits réservés.</p>
    </div>
</body>
</html> 