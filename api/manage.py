import argparse
import subprocess
import sys
from pathlib import Path


def run_command(cmd):
    """Run a shell command and handle errors."""
    try:
        # Run commands from the project root where alembic.ini is located
        result = subprocess.run(cmd, shell=True, check=True, capture_output=True, text=True, cwd=Path(__file__).parent)
        if result.stdout:
            print(result.stdout)
        return True
    except subprocess.CalledProcessError as e:
        print(f"Error: {e}")
        if e.stderr:
            print(f"Details: {e.stderr}")
        return False


def run_command_with_output(cmd):
    """Run a shell command with real-time output, showing both stdout and stderr."""
    try:
        # Run commands from the project root, streaming output directly to terminal
        result = subprocess.run(cmd, shell=True, cwd=Path(__file__).parent)
        return result.returncode == 0
    except Exception as e:
        print(f"Error running command: {e}")
        return False


def create_migration(message):
    """Create a new migration with auto-detection of model changes."""
    if not message:
        print("Error: Migration message is required")
        return False
    
    print(f"Creating migration: {message}")
    return run_command(f'alembic -c migrations/alembic.ini revision --autogenerate -m "{message}"')


def upgrade_db(revision="head"):
    """Upgrade database to latest or specific revision."""
    print(f"Upgrading database to: {revision}")
    return run_command(f"alembic -c migrations/alembic.ini upgrade {revision}")


def downgrade_db(revision):
    """Downgrade database to specific revision."""
    if not revision:
        print("Error: Target revision is required for downgrade")
        return False
    
    print(f"Downgrading database to: {revision}")
    return run_command(f"alembic -c migrations/alembic.ini downgrade {revision}")


def show_history():
    """Show migration history."""
    print("Migration history:")
    return run_command("alembic -c migrations/alembic.ini history --verbose")


def show_current():
    """Show current database revision."""
    print("Current database revision:")
    return run_command("alembic -c migrations/alembic.ini current --verbose")


def show_heads():
    """Show head revisions."""
    print("Head revisions:")
    return run_command("alembic -c migrations/alembic.ini heads --verbose")


def init_db():
    """Initialize database with all migrations."""
    print("Initializing database...")
    return upgrade_db()


def reset_db():
    """Reset database (downgrade to base, then upgrade to head)."""
    print("Resetting database...")
    if run_command("alembic -c migrations/alembic.ini downgrade base"):
        return run_command("alembic -c migrations/alembic.ini upgrade head")
    return False


def run_tests():
    """Run all tests."""
    print("Running tests...")
    return run_command_with_output("python -m pytest")


def run_tests_with_coverage():
    """Run tests with coverage report."""
    print("Running tests with coverage...")
    return run_command_with_output("python -m pytest --cov=app --cov-report=term-missing")


def create_user(name, email, password):
    """Create a new user."""
    import sys
    import os
    
    # Add the current directory to Python path
    sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
    
    try:
        from config import Config
        from sqlalchemy import create_engine
        from sqlalchemy.orm import sessionmaker
        from app import Base
        from app.models.user import User
        from app.services.auth import get_password_hash
        
        # Initialize database connection
        engine = create_engine(Config.SQLALCHEMY_DATABASE_URI)
        SessionLocal = sessionmaker(autocommit=False, autoflush=False, bind=engine)
        
        # Create tables if they don't exist
        Base.metadata.create_all(bind=engine)
        
        db = SessionLocal()
        try:
            # Check if user already exists
            existing_user = db.query(User).filter(User.email == email).first()
            if existing_user:
                print(f"Error: User with email {email} already exists")
                return False
            
            # Create new user
            hashed_password = get_password_hash(password)
            new_user = User(
                name=name,
                email=email,
                password=hashed_password
            )
            
            db.add(new_user)
            db.commit()
            db.refresh(new_user)
            
            print("User created successfully:")
            print(f"  ID: {new_user.id}")
            print(f"  Name: {new_user.name}")
            print(f"  Email: {new_user.email}")
            print(f"  Created: {new_user.created_at}")
            return True
            
        finally:
            db.close()
    except Exception as e:
        print(f"Error creating user: {e}")
        return False


def main():
    parser = argparse.ArgumentParser(description="Database management CLI")
    subparsers = parser.add_subparsers(dest='command', help='Available commands')

    # Create migration
    migrate_parser = subparsers.add_parser('db:migrate', help='Create a new migration')
    migrate_parser.add_argument('message', help='Migration description')

    # Upgrade
    upgrade_parser = subparsers.add_parser('db:upgrade', help='Upgrade database')
    upgrade_parser.add_argument('revision', nargs='?', default='head', help='Target revision (default: head)')

    # Downgrade
    downgrade_parser = subparsers.add_parser('db:downgrade', help='Downgrade database')
    downgrade_parser.add_argument('revision', help='Target revision')

    # History
    subparsers.add_parser('db:history', help='Show migration history')

    # Current
    subparsers.add_parser('db:current', help='Show current revision')

    # Heads
    subparsers.add_parser('db:heads', help='Show head revisions')

    # Init
    subparsers.add_parser('db:init', help='Initialize database')

    # Reset
    subparsers.add_parser('db:reset', help='Reset database (downgrade to base, then upgrade)')

    # Test commands
    subparsers.add_parser('test', help='Run all tests')
    subparsers.add_parser('test:cov', help='Run tests with coverage report')

    # User management
    user_parser = subparsers.add_parser('user:create', help='Create a new user')
    user_parser.add_argument('name', help='User name')
    user_parser.add_argument('email', help='User email')
    user_parser.add_argument('password', help='User password')

    args = parser.parse_args()

    if not args.command:
        parser.print_help()
        return

    # Command mapping
    commands = {
        'db:migrate': lambda: create_migration(args.message),
        'db:upgrade': lambda: upgrade_db(args.revision),
        'db:downgrade': lambda: downgrade_db(args.revision),
        'db:history': show_history,
        'db:current': show_current,
        'db:heads': show_heads,
        'db:init': init_db,
        'db:reset': reset_db,
        'test': run_tests,
        'test:cov': run_tests_with_coverage,
        'user:create': lambda: create_user(args.name, args.email, args.password),
    }

    if args.command in commands:
        success = commands[args.command]()
        sys.exit(0 if success else 1)
    else:
        print(f"Unknown command: {args.command}")
        parser.print_help()
        sys.exit(1)


if __name__ == '__main__':
    main()