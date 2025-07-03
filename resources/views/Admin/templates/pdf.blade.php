<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $template->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .category {
            font-style: italic;
            color: #666;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .form-field {
            margin-bottom: 15px;
        }
        .form-label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        .form-input {
            border: 1px solid #ccc;
            padding: 8px;
            width: 100%;
            min-height: 20px;
        }
        .form-textarea {
            min-height: 100px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $template->name }}</h1>
        @if($template->category)
        <div class="category">Category: {{ $template->category->name }}</div>
        @endif
    </div>
    
    <div class="section">
        <div class="section-title">1. Basic Information</div>
        
        <div class="form-field">
            <div class="form-label">1.1 Submitted By:</div>
            <div class="form-input"></div>
        </div>
        
        <div class="form-field">
            <div class="form-label">1.2 Email:</div>
            <div class="form-input"></div>
        </div>
        
        <div class="form-field">
            <div class="form-label">1.3 Phone Number:</div>
            <div class="form-input"></div>
        </div>
        
        <div class="form-field">
            <div class="form-label">1.4 Organization Name:</div>
            <div class="form-input"></div>
        </div>
        
        <div class="form-field">
            <div class="form-label">1.5 Organization Type:</div>
            <div class="form-input"></div>
        </div>
        
        <div class="form-field">
            <div class="form-label">1.6 Address:</div>
            <div class="form-input"></div>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">2. Proposal Details</div>
        
        <div class="form-field">
            <div class="form-label">2.1 Proposal Title:</div>
            <div class="form-input"></div>
        </div>
        
        <div class="form-field">
            <div class="form-label">2.2 Executive Summary:</div>
            <div class="form-input form-textarea"></div>
        </div>
        
        <div class="form-field">
            <div class="form-label">2.3 Background:</div>
            <div class="form-input form-textarea"></div>
        </div>
        
        <div class="form-field">
            <div class="form-label">2.4 Proposed Activities:</div>
            <div class="form-input form-textarea"></div>
        </div>
        
        <div class="form-field">
            <div class="form-label">2.5 Goals and Objectives:</div>
            <div class="form-input form-textarea"></div>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">3. Project Implementation</div>
        
        <div class="form-field">
            <div class="form-label">3.1 Project Duration:</div>
            <div class="form-input"></div>
        </div>
        
        <div class="form-field">
            <div class="form-label">3.2 Implementation Timeline:</div>
            <table>
                <tr>
                    <th>Activity</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Responsible Person</th>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">4. Budget</div>
        
        <div class="form-field">
            <div class="form-label">4.1 Total Budget Requested (USD):</div>
            <div class="form-input"></div>
        </div>
        
        <div class="form-field">
            <div class="form-label">4.2 Budget Breakdown:</div>
            <table>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Unit Cost</th>
                    <th>Total Cost</th>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">5. Expected Outcomes</div>
        
        <div class="form-field">
            <div class="form-label">5.1 Expected Results:</div>
            <div class="form-input form-textarea"></div>
        </div>
        
        <div class="form-field">
            <div class="form-label">5.2 Monitoring and Evaluation Plan:</div>
            <div class="form-input form-textarea"></div>
        </div>
    </div>
    
    <div class="footer">
        <p>This is a proposal template form. Please fill out all fields and upload it to the system.</p>
        <p>{{ config('app.name') }} - Generated on {{ date('Y-m-d') }}</p>
    </div>
</body>
</html>