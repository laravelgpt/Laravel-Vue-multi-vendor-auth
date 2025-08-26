# Contributing to Laravel Vue Admin Dashboard

Thank you for your interest in contributing to Laravel Vue Admin Dashboard! This document provides guidelines and information for contributors.

## 🤝 **How to Contribute**

### **Types of Contributions**
- 🐛 **Bug Reports**: Report bugs and issues
- ✨ **Feature Requests**: Suggest new features
- 📝 **Documentation**: Improve documentation
- 🔧 **Code Contributions**: Submit code changes
- 🧪 **Testing**: Add or improve tests
- 🎨 **UI/UX**: Design improvements

## 🚀 **Getting Started**

### **1. Fork the Repository**
1. Go to the [main repository](https://github.com/laravelgpt/Laravel-Vue-multi-vendor-auth)
2. Click the "Fork" button
3. Clone your forked repository:
```bash
git clone https://github.com/YOUR_USERNAME/Laravel-Vue-multi-vendor-auth.git
cd Laravel-Vue-multi-vendor-auth
```

### **2. Set Up Development Environment**
```bash
# Install dependencies
composer install
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Set up database
touch database/database.sqlite
php artisan migrate

# Build assets
npm run dev
```

### **3. Create a Feature Branch**
```bash
git checkout -b feature/your-feature-name
# or
git checkout -b fix/your-bug-fix
```

## 📝 **Development Guidelines**

### **Code Style**
- Follow [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standards
- Use Laravel Pint for PHP code formatting
- Follow Vue.js style guide for frontend code
- Use TypeScript for all Vue components

### **PHP Code Standards**
```bash
# Format PHP code
vendor/bin/pint

# Check code style
vendor/bin/pint --test
```

### **JavaScript/Vue Code Standards**
```bash
# Format JavaScript/Vue code
npm run format

# Lint code
npm run lint
```

### **Testing**
- Write tests for all new features
- Ensure all existing tests pass
- Follow Pest testing conventions

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/YourTest.php

# Run tests with coverage
php artisan test --coverage
```

## 🔧 **Making Changes**

### **1. Make Your Changes**
- Write clean, readable code
- Add comments for complex logic
- Follow existing code patterns
- Update documentation if needed

### **2. Test Your Changes**
```bash
# Run the test suite
php artisan test

# Build assets
npm run build

# Check for any linting errors
npm run lint
vendor/bin/pint --test
```

### **3. Commit Your Changes**
Use conventional commit messages:
```bash
git commit -m "feat: add new social login provider"
git commit -m "fix: resolve authentication issue"
git commit -m "docs: update installation guide"
git commit -m "test: add tests for user profile"
```

### **4. Push Your Changes**
```bash
git push origin feature/your-feature-name
```

## 📋 **Pull Request Process**

### **1. Create a Pull Request**
1. Go to your forked repository on GitHub
2. Click "New Pull Request"
3. Select the base branch (usually `master`)
4. Fill out the pull request template

### **2. Pull Request Template**
```markdown
## Description
Brief description of the changes

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Documentation update
- [ ] Test addition
- [ ] Code refactoring

## Testing
- [ ] All tests pass
- [ ] New tests added for new functionality
- [ ] Manual testing completed

## Checklist
- [ ] Code follows project style guidelines
- [ ] Self-review completed
- [ ] Documentation updated
- [ ] No breaking changes introduced
```

### **3. Review Process**
- Maintainers will review your PR
- Address any feedback or requested changes
- Ensure CI/CD checks pass
- Wait for approval before merging

## 🎯 **Development Focus Areas**

### **High Priority**
- Security improvements
- Bug fixes
- Performance optimizations
- Accessibility improvements

### **Medium Priority**
- New features
- UI/UX enhancements
- Documentation improvements
- Test coverage

### **Low Priority**
- Code refactoring
- Minor UI tweaks
- Additional examples

## 🐛 **Reporting Issues**

### **Bug Reports**
When reporting bugs, please include:
- Clear description of the issue
- Steps to reproduce
- Expected vs actual behavior
- Environment details (OS, PHP version, etc.)
- Screenshots if applicable

### **Feature Requests**
When requesting features, please include:
- Clear description of the feature
- Use case and benefits
- Implementation suggestions if possible
- Mockups or examples if applicable

## 📚 **Documentation**

### **Code Documentation**
- Use PHPDoc for PHP functions and classes
- Add JSDoc comments for JavaScript functions
- Include examples in documentation

### **README Updates**
- Update README.md for new features
- Add installation instructions for new dependencies
- Update configuration examples

## 🔒 **Security**

### **Security Issues**
- Report security vulnerabilities privately
- Do not disclose security issues publicly
- Follow responsible disclosure practices

### **Security Guidelines**
- Never commit sensitive data (API keys, passwords)
- Use environment variables for configuration
- Validate all user inputs
- Follow OWASP security guidelines

## 🏷️ **Labels and Milestones**

### **Issue Labels**
- `bug`: Something isn't working
- `enhancement`: New feature or request
- `documentation`: Improvements to documentation
- `good first issue`: Good for newcomers
- `help wanted`: Extra attention is needed
- `question`: Further information is requested

### **Pull Request Labels**
- `ready for review`: Ready for maintainer review
- `work in progress`: Still being worked on
- `needs review`: Requires review from maintainers

## 🎉 **Recognition**

### **Contributors**
- All contributors will be listed in the README
- Significant contributions will be highlighted
- Contributors will be added to the project's contributors list

### **Code of Conduct**
- Be respectful and inclusive
- Use welcoming and inclusive language
- Be collaborative and constructive
- Focus on what is best for the community

## 📞 **Getting Help**

### **Questions and Support**
- Check existing issues and discussions
- Review documentation and examples
- Ask questions in issues or discussions
- Join community channels if available

### **Resources**
- [Laravel Documentation](https://laravel.com/docs)
- [Vue.js Documentation](https://vuejs.org/guide/)
- [Inertia.js Documentation](https://inertiajs.com/)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)

## 📄 **License**

By contributing to this project, you agree that your contributions will be licensed under the MIT License.

---

Thank you for contributing to Laravel Vue Admin Dashboard! 🚀
