# Generated by Django 5.1.3 on 2024-12-04 15:09

from django.db import migrations, models


class Migration(migrations.Migration):

    initial = True

    dependencies = [
    ]

    operations = [
        migrations.CreateModel(
            name='User',
            fields=[
                ('id', models.BigAutoField(auto_created=True, primary_key=True, serialize=False, verbose_name='ID')),
                ('age', models.BigIntegerField()),
                ('name', models.CharField(max_length=250)),
            ],
        ),
    ]