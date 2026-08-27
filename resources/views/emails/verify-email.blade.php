<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Verify Your Email | UCU Smart+
    </title>

</head>


<body
    style="
        margin: 0;
        padding: 0;
        background-color: #f3f4f6;
        font-family: Arial, Helvetica, sans-serif;
        color: #1f2937;
    ">


    <!-- ========================================================= -->
    <!-- MAIN WRAPPER -->
    <!-- ========================================================= -->

    <table
        width="100%"
        cellpadding="0"
        cellspacing="0"
        border="0"
        style="
            background-color: #f3f4f6;
            padding: 40px 15px;
        ">

        <tr>

            <td align="center">


                <!-- ================================================= -->
                <!-- EMAIL CONTAINER -->
                <!-- ================================================= -->

                <table
                    width="100%"
                    cellpadding="0"
                    cellspacing="0"
                    border="0"
                    style="
                        max-width: 600px;
                        background-color: #ffffff;
                        border-radius: 16px;
                        overflow: hidden;
                        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
                    ">


                    <!-- ============================================= -->
                    <!-- HEADER -->
                    <!-- ============================================= -->

                    <tr>

                        <td
                            style="
                                background-color: #0E2958;
                                padding: 30px 35px;
                            ">

                            <table
                                width="100%"
                                cellpadding="0"
                                cellspacing="0"
                                border="0">

                                <tr>

                                    <td>

                                        <div
                                            style="
                                                font-size: 24px;
                                                font-weight: 800;
                                                color: #ffffff;
                                                letter-spacing: 0.5px;
                                            ">

                                            UCU SMART+

                                        </div>


                                        <div
                                            style="
                                                color: #bfdbfe;
                                                font-size: 13px;
                                                margin-top: 5px;
                                            ">

                                            Classroom Availability &
                                            Campus Navigation System

                                        </div>

                                    </td>

                                </tr>

                            </table>

                        </td>

                    </tr>


                    <!-- ============================================= -->
                    <!-- CONTENT -->
                    <!-- ============================================= -->

                    <tr>

                        <td
                            style="
                                padding: 40px 35px 35px;
                            ">


                            <!-- Icon -->

                            <div
                                style="
                                    width: 56px;
                                    height: 56px;
                                    line-height: 56px;
                                    text-align: center;
                                    border-radius: 14px;
                                    background-color: #e8f1f5;
                                    color: #0E4C6B;
                                    font-size: 25px;
                                    margin-bottom: 22px;
                                ">

                                ✓

                            </div>


                            <!-- Heading -->

                            <h1
                                style="
                                    margin: 0;
                                    color: #0E2958;
                                    font-size: 28px;
                                    line-height: 1.3;
                                    font-weight: 800;
                                ">

                                Verify Your Email

                            </h1>


                            <!-- Greeting -->

                            <p
                                style="
                                    margin: 18px 0 0;
                                    color: #374151;
                                    font-size: 15px;
                                    line-height: 1.7;
                                ">

                                Hello,

                                @if(isset($user->first_name))
                                    <strong>{{ $user->first_name }}</strong>
                                @endif

                            </p>


                            <p
                                style="
                                    margin: 10px 0 0;
                                    color: #6b7280;
                                    font-size: 15px;
                                    line-height: 1.7;
                                ">

                                Thank you for registering with
                                <strong style="color: #0E4C6B;">
                                    UCU Smart+
                                </strong>.

                                Please verify your email address to
                                activate your account and continue using
                                the system.

                            </p>


                            <!-- ===================================== -->
                            <!-- BUTTON -->
                            <!-- ===================================== -->

                            <table
                                cellpadding="0"
                                cellspacing="0"
                                border="0"
                                style="
                                    margin: 30px 0;
                                ">

                                <tr>

                                    <td
                                        align="center"
                                        style="
                                            border-radius: 10px;
                                            background-color: #0E2958;
                                        ">

                                        <a
                                            href="{{ $url }}"
                                            style="
                                                display: inline-block;
                                                padding: 14px 26px;
                                                color: #ffffff;
                                                background-color: #0E2958;
                                                border-radius: 10px;
                                                font-size: 14px;
                                                font-weight: 700;
                                                text-decoration: none;
                                            ">

                                            Verify Email Address

                                        </a>

                                    </td>

                                </tr>

                            </table>


                            <!-- ===================================== -->
                            <!-- EXPLANATION -->
                            <!-- ===================================== -->

                            <p
                                style="
                                    margin: 0;
                                    color: #6b7280;
                                    font-size: 13px;
                                    line-height: 1.7;
                                ">

                                After verification, you will be able to
                                access your UCU Smart+ account.

                            </p>


                            <!-- ===================================== -->
                            <!-- FALLBACK URL -->
                            <!-- ===================================== -->

                            <div
                                style="
                                    margin-top: 25px;
                                    padding: 16px;
                                    background-color: #f8fafc;
                                    border: 1px solid #e5e7eb;
                                    border-radius: 10px;
                                ">

                                <p
                                    style="
                                        margin: 0 0 7px;
                                        color: #6b7280;
                                        font-size: 12px;
                                        font-weight: 600;
                                    ">

                                    Button not working?

                                </p>

                                <p
                                    style="
                                        margin: 0;
                                        color: #6b7280;
                                        font-size: 11px;
                                        line-height: 1.6;
                                        word-break: break-all;
                                    ">

                                    Copy and paste the following link
                                    into your browser:

                                </p>

                                <p
                                    style="
                                        margin: 8px 0 0;
                                        color: #0E4C6B;
                                        font-size: 11px;
                                        line-height: 1.6;
                                        word-break: break-all;
                                    ">

                                    {{ $url }}

                                </p>

                            </div>


                            <!-- ===================================== -->
                            <!-- SECURITY NOTICE -->
                            <!-- ===================================== -->

                            <div
                                style="
                                    margin-top: 25px;
                                    padding-top: 20px;
                                    border-top: 1px solid #e5e7eb;
                                ">

                                <p
                                    style="
                                        margin: 0;
                                        color: #6b7280;
                                        font-size: 12px;
                                        line-height: 1.6;
                                    ">

                                    <strong style="color: #374151;">
                                        Didn't create this account?
                                    </strong>

                                    You can safely ignore this email.
                                    No further action is required.

                                </p>

                            </div>


                        </td>

                    </tr>


                    <!-- ============================================= -->
                    <!-- FOOTER -->
                    <!-- ============================================= -->

                    <tr>

                        <td
                            style="
                                background-color: #f8fafc;
                                border-top: 1px solid #e5e7eb;
                                padding: 22px 35px;
                                text-align: center;
                            ">

                            <p
                                style="
                                    margin: 0;
                                    color: #0E2958;
                                    font-size: 13px;
                                    font-weight: 700;
                                ">

                                UCU SMART+

                            </p>

                            <p
                                style="
                                    margin: 5px 0 0;
                                    color: #9ca3af;
                                    font-size: 11px;
                                ">

                                Urdaneta City University

                            </p>

                            <p
                                style="
                                    margin: 12px 0 0;
                                    color: #9ca3af;
                                    font-size: 10px;
                                ">

                                © {{ date('Y') }} UCU Smart+.
                                All rights reserved.

                            </p>

                        </td>

                    </tr>


                </table>


            </td>

        </tr>

    </table>


</body>

</html>