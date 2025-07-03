<!DOCTYPE html>
<html>
<head>
    <title>Budget Constraint Notification</title>
</head>
<body>
    <p>Dear {{ auth()->user()->name }},</p>

    <p>Your project proposal titled "<strong>{{ $proposalTitle }}</strong>" under the category "<strong>{{ $categoryName }}</strong>" was automatically rejected.</p>

    <p>This is because the total approved budget for this category has already been allocated and remaining funds cannot fully cover the requested budget.</p>

    <p>Requested Budget: ${{ number_format($budgetRequested, 2) }}<br>
Category Budget: ${{ number_format($totalBudget, 2) }}<br>
Remaining Balance: ${{ number_format($remainingBalance, 2) }}</p>
    <p>Please contact the admin for further clarification.</p>

    <p>Thank you for your understanding.</p>
</body>
</html>
