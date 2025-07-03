@extends('layouts.app')

@section('content')
<style>
    .category-form-card {
        max-width: 600px;
        margin: 2rem auto;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgb(0 0 0 / 0.1);
        background: #fff;
        transition: box-shadow 0.3s ease;
    }
    .category-form-card:hover {
        box-shadow: 0 12px 36px rgb(0 0 0 / 0.15);
    }
    label.form-label {
        font-weight: 600;
        color: #3b3b3b;
    }
    input.form-control,
    textarea.form-control {
        border-radius: 8px;
        border: 1.5px solid #ced4da;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    input.form-control:focus,
    textarea.form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 8px rgb(13 110 253 / 0.25);
    }
    .btn-submit {
        border-radius: 50px;
        padding: 0.6rem 2rem;
        font-weight: 600;
        font-size: 1.1rem;
        box-shadow: 0 4px 12px rgb(13 110 253 / 0.3);
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }
    .btn-submit:hover {
        background-color: #0b5ed7;
        box-shadow: 0 6px 16px rgb(11 94 215 / 0.5);
    }
    .invalid-feedback {
        font-size: 0.9rem;
        color: #dc3545;
        transition: opacity 0.3s ease;
    }
    .form-title {
        color: #0d6efd;
        font-weight: 700;
        margin-bottom: 1.8rem;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 1.2px;
    }
    .tooltip-inner {
        max-width: 200px;
        text-align: left;
        font-size: 0.9rem;
        background-color: #0d6efd;
        color: white;
        padding: 0.4rem 0.6rem;
        border-radius: 6px;
    }
</style>

<div class="category-form-card" role="form" aria-labelledby="formTitle">
    <h2 id="formTitle" class="form-title">Create Project Category</h2>

    <form method="POST" action="{{ route('admin.project-categories.store') }}" novalidate>
        @csrf

        <div class="mb-4">
            <label for="name" class="form-label">
                Category Name <span class="text-danger">*</span>
                <i
                  class="bi bi-info-circle-fill text-primary"
                  tabindex="0"
                  data-bs-toggle="tooltip"
                  data-bs-placement="right"
                  title="Enter a descriptive and unique category name."
                  role="tooltip"
                  aria-label="Info: Enter a descriptive and unique category name."
                  style="cursor:pointer;"
                ></i>
            </label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                class="form-control @error('name') is-invalid @enderror"
                required
                placeholder="E.g., Education, Healthcare"
                autofocus
                aria-describedby="nameHelp"
                aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
            >
            <div id="nameHelp" class="form-text">This will help identify the project category.</div>
            @error('name')
                <div class="invalid-feedback" role="alert">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="budget" class="form-label">
                Budget ($) <span class="text-danger">*</span>
                <i
                  class="bi bi-info-circle-fill text-primary"
                  tabindex="0"
                  data-bs-toggle="tooltip"
                  data-bs-placement="right"
                  title="Set the total budget available for this category."
                  role="tooltip"
                  aria-label="Info: Set the total budget available for this category."
                  style="cursor:pointer;"
                ></i>
            </label>
            <input
                type="number"
                id="budget"
                name="budget"
                value="{{ old('budget') }}"
                class="form-control @error('budget') is-invalid @enderror"
                step="0.01"
                min="0"
                required
                placeholder="10000.00"
                aria-describedby="budgetHelp"
                aria-invalid="{{ $errors->has('budget') ? 'true' : 'false' }}"
            >
            <div id="budgetHelp" class="form-text">Example: 10000.00</div>
            @error('budget')
                <div class="invalid-feedback" role="alert">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="description" class="form-label">Description (optional)</label>
            <textarea
                id="description"
                name="description"
                class="form-control @error('description') is-invalid @enderror"
                rows="4"
                placeholder="Briefly describe this category"
                aria-describedby="descriptionHelp"
            >{{ old('description') }}</textarea>
            <div id="descriptionHelp" class="form-text">Optional: Provide details to clarify the category.</div>
            @error('description')
                <div class="invalid-feedback" role="alert">{{ $message }}</div>
            @enderror
        </div>
        <div class="d-flex justify-content-between align-items-center">
    <a href="{{ route('admin.project-categories.index') }}" class="btn btn-secondary" aria-label="Go back to categories list">
        <i class="bi bi-arrow-left-circle me-1"></i> Back
    </a>
    <div>

       
            <button type="submit" class="btn btn-primary btn-submit" aria-label="Submit form to create category">
                <i class="bi bi-plus-circle me-2"></i> Create Category
            </button>
        </div>
    </form>
</div>

<script>
    (() => {
        'use strict';

        // Enable Bootstrap tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(tooltipTriggerEl => {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Simple live validation example for category name (can be extended)
        const form = document.querySelector('form');
        const nameInput = form.querySelector('#name');
        const budgetInput = form.querySelector('#budget');

        function validateName() {
            if (!nameInput.value.trim()) {
                nameInput.classList.add('is-invalid');
                nameInput.setAttribute('aria-invalid', 'true');
                return false;
            } else {
                nameInput.classList.remove('is-invalid');
                nameInput.setAttribute('aria-invalid', 'false');
                return true;
            }
        }

        function validateBudget() {
            if (budgetInput.value === '' || Number(budgetInput.value) < 0) {
                budgetInput.classList.add('is-invalid');
                budgetInput.setAttribute('aria-invalid', 'true');
                return false;
            } else {
                budgetInput.classList.remove('is-invalid');
                budgetInput.setAttribute('aria-invalid', 'false');
                return true;
            }
        }

        nameInput.addEventListener('input', validateName);
        budgetInput.addEventListener('input', validateBudget);

        form.addEventListener('submit', e => {
            const validName = validateName();
            const validBudget = validateBudget();

            if (!validName || !validBudget) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    })();
</script>
@endsection
